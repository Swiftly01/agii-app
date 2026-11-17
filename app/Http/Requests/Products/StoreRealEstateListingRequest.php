<?php

namespace App\Http\Requests\Products;

use App\Services\Products\RealEstate\RealEstateFormSchema;
use Illuminate\Foundation\Http\FormRequest;

final class StoreRealEstateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // An unticked checkbox is absent from the payload, so default it to false
    protected function prepareForValidation(): void
    {
        $this->merge(['service_charge' => $this->boolean('service_charge')]);
    }

    public function rules(): array
    {
        return app(RealEstateFormSchema::class)->rules();
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Please add at least ' . config('real_estate_form.photos.min') . ' photos.',
            'images.min' => 'Please add at least ' . config('real_estate_form.photos.min') . ' photos.',
            'images.*.max' => 'Each picture must not exceed ' . config('real_estate_form.photos.max_size_mb') . ' Mb.',
        ];
    }
}
