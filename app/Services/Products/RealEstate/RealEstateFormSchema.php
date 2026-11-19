<?php

namespace App\Services\Products\RealEstate;

use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

final class RealEstateFormSchema
{
    public function __construct(private readonly string $form = 'real_estate_form') {}

    // Schema for a category slug, only categories listed in config/listing_forms.php have a form
    public static function forCategory(string $slug): self
    {
        return new self(config("listing_forms.{$slug}") ?? abort(404));
    }

    // Forms offered under a category tile: its own form plus the forms of its active subcategories
    public static function menuFor(Category $category): array
    {
        return collect([$category->slug, ...$category->children()->active()->pluck('slug')->all()])
            ->filter(fn (string $slug) => config("listing_forms.{$slug}"))
            ->map(fn (string $slug) => [
                'label' => self::forCategory($slug)->categoryLabel(),
                'url' => route("products.create.{$slug}"),
            ])
            ->values()
            ->all();
    }

    // Normalised field definitions keyed by field name
    public function fields(): Collection
    {
        return collect($this->config('fields'))
            ->map(fn (array $field, string $name) => $this->normalise($name, $field));
    }

    // Fields grouped by section in the configured display order
    public function sections(): Collection
    {
        $fields = $this->fields();

        return collect($this->config('sections'))
            ->mapWithKeys(fn (string $section) => [$section => $fields->where('section', $section)]);
    }

    public function photos(): array
    {
        return $this->config('photos');
    }

    public function videos(): array
    {
        return $this->config('videos');
    }

    public function categorySlug(): string
    {
        return $this->config('category_slug');
    }

    // Name shown as the fixed category on the form
    public function categoryLabel(): string
    {
        return $this->config('category_label');
    }

    // Validation rules for every schema field plus the photo and video uploads, the photo minimum is checked in mediaError()
    public function rules(): array
    {
        $photos = $this->photos();
        $videos = $this->videos();

        return [
            ...$this->fields()->mapWithKeys(fn (array $field, string $name) => $this->fieldRules($name, $field))->all(),
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:' . implode(',', $photos['mimes']), 'max:' . $photos['max_size_mb'] * 1024],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimetypes:' . implode(',', $videos['mime_types']), 'max:' . $videos['max_size_mb'] * 1024],
        ];
    }

    // Checks the final photo and video totals, returns an error message or null when the media is fine
    public function mediaError(int $photoCount, int $videoCount): ?string
    {
        $photos = $this->photos();
        $videos = $this->videos();
        $videoReplaces = $photos['optional_with_video'] ?? false;

        if ($photoCount > $photos['max']) {
            return "You can add up to {$photos['max']} photos.";
        }

        if ($videoCount > $videos['max']) {
            return "You can add up to {$videos['max']} videos.";
        }

        if ($photoCount < $photos['min'] && ! ($videoReplaces && $videoCount > 0)) {
            return $videoReplaces
                ? "Please add at least {$photos['min']} photos or upload a video."
                : "Please add at least {$photos['min']} photos.";
        }

        return null;
    }

    // Initial values for the form, config defaults first then user profile data
    public function defaults(User $user): array
    {
        return $this->fields()
            ->map(fn (array $field) => $field['default'] ?? ($field['default_from'] ? $user->{$field['default_from']} : null))
            ->all();
    }

    // Form values for an existing listing, the inverse of how RealEstateListingService stores them
    public function valuesFor(Product $product): array
    {
        $specifications = $product->specifications ?? [];

        return $this->fields()
            ->map(fn (array $field, string $name) => isset($field['column'])
                ? $product->{$field['column']}
                : ($specifications[$name] ?? $field['default'] ?? null))
            ->all();
    }

    private function config(string $key): mixed
    {
        return config("{$this->form}.{$key}");
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
