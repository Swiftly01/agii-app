<?php

namespace App\Services\Products\RealEstate;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\Products\ListingImageUploader;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class RealEstateListingService
{
    public function __construct(
        private readonly RealEstateFormSchema $schema,
        private readonly ListingImageUploader $uploader,
    ) {}

    // Creates a pending listing from validated form data and the uploaded photos
    public function create(User $user, array $data, array $images): Product
    {
        $category = Category::where('slug', $this->schema->categorySlug())->firstOrFail();

        return Product::create([
            'user_id' => $user->id,
            'store_id' => $user->store?->id,
            'category_id' => $category->id,
            'slug' => $this->uniqueSlug($data['title']),
            'location' => $this->location($data),
            'quantity' => 1,
            'negotiable' => ($data['negotiation'] ?? null) === 'Yes',
            'images' => $this->uploader->store($images),
            'specifications' => $this->specifications($data),
            // Listings go live only after admin approval, see ProductApprovalService
            'status' => 'pending',
            'meta_title' => $data['title'],
            'meta_description' => Str::limit($data['description'], 160),
            ...$this->columns($data),
        ]);
    }

    // Values for fields that map straight onto a products column
    private function columns(array $data): array
    {
        return $this->schema->fields()
            ->filter(fn (array $field) => isset($field['column']))
            ->mapWithKeys(fn (array $field, string $name) => [$field['column'] => $data[$name] ?? null])
            ->all();
    }

    // Everything without a dedicated column is kept as a specification
    private function specifications(array $data): array
    {
        $columnFields = $this->schema->fields()->filter(fn (array $f) => isset($f['column']))->keys()->all();

        return array_filter(
            Arr::except($data, [...$columnFields, 'images']),
            fn ($value) => filled($value) || $value === false,
        );
    }

    private function location(array $data): string
    {
        return collect([$data['address'] ?? null, $data['locality'] ?? null])->filter()->implode(', ');
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);

        return Product::where('slug', $slug)->exists() ? $slug . '-' . Str::lower(Str::random(6)) : $slug;
    }
}
