<?php

namespace App\Services\Products\RealEstate;

use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

final class RealEstateFormSchema
{
    // Normalised field definitions keyed by field name
    public function fields(): Collection
    {
        return collect(config('real_estate_form.fields'))
            ->map(fn (array $field, string $name) => $this->normalise($name, $field));
    }

    // Fields grouped by section in the configured display order
    public function sections(): Collection
    {
        $fields = $this->fields();

        return collect(config('real_estate_form.sections'))
            ->mapWithKeys(fn (string $section) => [$section => $fields->where('section', $section)]);
    }

    public function photos(): array
    {
        return config('real_estate_form.photos');
    }

    public function categorySlug(): string
    {
        return config('real_estate_form.category_slug');
    }

    // Validation rules for every schema field plus the photo uploads
    public function rules(): array
    {
        $photos = $this->photos();

        return [
            ...$this->fields()->mapWithKeys(fn (array $field, string $name) => $this->fieldRules($name, $field))->all(),
            'images' => ['required', 'array', "min:{$photos['min']}", "max:{$photos['max']}"],
            'images.*' => ['image', 'mimes:' . implode(',', $photos['mimes']), 'max:' . $photos['max_size_mb'] * 1024],
        ];
    }

    // Initial values for the form, config defaults first then user profile data
    public function defaults(User $user): array
    {
        return $this->fields()
            ->map(fn (array $field) => $field['default'] ?? ($field['default_from'] ? $user->{$field['default_from']} : null))
            ->all();
    }

    private function normalise(string $name, array $field): array
    {
        $field += ['name' => $name, 'required' => false, 'options' => [], 'popular' => [], 'any' => false, 'default_from' => null];
        $field['name'] = $name;

        if (isset($field['options_source'])) {
            $field['options'] = $this->resolveSource($field['options_source']);
        }

        return $field;
    }

    // Dynamic option lists that come from the database
    private function resolveSource(string $source): array
    {
        return match ($source) {
            'localities' => Location::query()->whereNotNull('lga')->distinct()->orderBy('lga')->pluck('lga')->all(),
            default => [],
        };
    }

    private function fieldRules(string $name, array $field): array
    {
        $presence = $field['required'] ? 'required' : 'nullable';

        return match ($field['type']) {
            'text', 'textarea' => [$name => [$presence, 'string', 'max:' . ($field['max'] ?? 255)]],
            'url' => [$name => [$presence, 'url', 'max:' . ($field['max'] ?? 255)]],
            'number' => [$name => [$presence, 'numeric', 'min:0', 'max:' . ($field['max'] ?? 99999999)]],
            'checkbox' => [$name => ['boolean']],
            'multiselect' => [
                $name => ['nullable', 'array'],
                "{$name}.*" => [Rule::in($field['options'])],
            ],
            default => [$name => [$presence, ...$this->optionRule($field)]],
        };
    }

    // Free text is accepted when a dynamic source has no rows yet
    private function optionRule(array $field): array
    {
        return $field['options'] === [] ? ['string', 'max:255'] : [Rule::in($field['options'])];
    }
}
