<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use App\Services\Products\RealEstate\RealEstateFormSchema;
use Illuminate\Support\Arr;

final class UpdateRealEstateListingRequest extends StoreRealEstateListingRequest
{
    private ?Product $product = null;

    // Only the owner or an admin can edit, a missing or non real estate listing is a 404
    public function authorize(): bool
    {
        $this->product();

        return true;
    }

    public function product(): Product
    {
        return $this->product ??= Product::realEstate()
            ->where('slug', $this->route('slug'))
            ->manageableBy($this->user())
            ->firstOrFail();
    }

    // The form follows the listing's own category
    public function schema(): RealEstateFormSchema
    {
        return RealEstateFormSchema::forCategory($this->product()->category->slug);
    }

    // Same rules as posting plus the photos and videos ticked for removal
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string'],
            'remove_videos' => ['nullable', 'array'],
            'remove_videos.*' => ['string'],
        ];
    }

    // Only paths that really belong to this listing can be removed
    public function removedImages(): array
    {
        return array_values(array_intersect(Arr::wrap($this->input('remove_images', [])), $this->product()->storedImages()));
    }

    public function removedVideos(): array
    {
        return array_values(array_intersect(Arr::wrap($this->input('remove_videos', [])), $this->product()->videos ?? []));
    }

    // Kept plus new photos
    protected function photoCount(): int
    {
        return count($this->product()->storedImages()) - count($this->removedImages()) + parent::photoCount();
    }

    // Kept plus new videos
    protected function videoCount(): int
    {
        return count($this->product()->videos ?? []) - count($this->removedVideos()) + parent::videoCount();
    }
}
