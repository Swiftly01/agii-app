<?php

namespace App\Services\Products\RealEstate;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\Products\ListingImageUploader;
use App\Services\Products\ListingVideoUploader;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class RealEstateListingService
{
    public function __construct(
        private readonly ListingImageUploader $images,
        private readonly ListingVideoUploader $videos,
    ) {}

    // Creates a pending listing from validated form data and the uploaded photos and videos
    public function create(RealEstateFormSchema $schema, User $user, array $data, array $images, array $videos): Product
    {
        $category = Category::where('slug', $schema->categorySlug())->firstOrFail();

        return Product::create([
            'user_id' => $user->id,
            'store_id' => $user->store?->id,
            'category_id' => $category->id,
            'slug' => $this->uniqueSlug($data['title']),
            'quantity' => 1,
            'images' => $this->images->store($images),
            'videos' => $this->videos->store($videos),
            // Listings go live only after admin approval, see ProductApprovalService
            'status' => 'pending',
            ...$this->attributes($schema, $data),
        ]);
    }

    // Applies the edited form to a listing, kept media stays and removed files are deleted from disk
    public function update(RealEstateFormSchema $schema, Product $product, array $data, array $newImages, array $newVideos, array $removedImages, array $removedVideos): Product
    {
        $keptImages = array_values(array_diff($product->storedImages(), $removedImages));
        $keptVideos = array_values(array_diff($product->videos ?? [], $removedVideos));

        $product->update([
            'images' => [...$keptImages, ...$this->images->store($newImages)],
            'videos' => [...$keptVideos, ...$this->videos->store($newVideos)],
            ...$this->attributes($schema, $data),
        ]);

        // A live listing goes back to admin review after an edit, same as the generic edit flow
        if ($product->status === 'active') {
            $product->forceFill(['status' => 'pending'])->save();
        }

        $this->images->delete($removedImages);
        $this->videos->delete($removedVideos);

        return $product;
    }

    // Attributes derived from the form data, shared by create and update
    private function attributes(RealEstateFormSchema $schema, array $data): array
    {
        return [
            'location' => $this->location($data),
            'negotiable' => ($data['negotiation'] ?? null) === 'Yes',
            'specifications' => $this->specifications($schema, $data),
            'meta_title' => $data['title'],
            'meta_description' => Str::limit($data['description'], 160),
            ...$this->columns($schema, $data),
        ];
    }

    // Values for fields that map straight onto a products column
    private function columns(RealEstateFormSchema $schema, array $data): array
    {
        return $schema->fields()
            ->filter(fn (array $field) => isset($field['column']))
            ->mapWithKeys(fn (array $field, string $name) => [$field['column'] => $data[$name] ?? null])
            ->all();
    }

    // Everything without a dedicated column is kept as a specification
    private function specifications(RealEstateFormSchema $schema, array $data): array
    {
        $columnFields = $schema->fields()->filter(fn (array $f) => isset($f['column']))->keys()->all();

        return array_filter(
            Arr::except($data, [...$columnFields, 'images', 'videos', 'remove_images', 'remove_videos']),
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
