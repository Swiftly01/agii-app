<?php

namespace App\Http\Requests\Products;

use App\Services\Products\RealEstate\RealEstateFormSchema;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class StoreRealEstateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // The form is chosen by the category slug the route was registered with
    public function schema(): RealEstateFormSchema
    {
        return RealEstateFormSchema::forCategory($this->route('category'));
    }

    // An unticked checkbox is absent from the payload, so default it to false
    protected function prepareForValidation(): void
    {
        if ($this->schema()->fields()->has('service_charge')) {
            $this->merge(['service_charge' => $this->boolean('service_charge')]);
        }
    }

    public function rules(): array
    {
        return $this->schema()->rules();
    }

    public function messages(): array
    {
        return [
            'images.*.max' => 'Each picture must not exceed ' . $this->schema()->photos()['max_size_mb'] . ' Mb.',
            'videos.*.max' => 'Each video must not exceed ' . $this->schema()->videos()['max_size_mb'] . ' Mb.',
            'videos.*.mimetypes' => 'Videos must be ' . strtoupper(implode(', ', $this->schema()->videos()['mimes'])) . ' files.',
        ];
    }

    // Photos are required unless a video replaces them, see RealEstateFormSchema::mediaError()
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $error = $this->schema()->mediaError($this->photoCount(), $this->videoCount());

            if ($error) {
                $validator->errors()->add('images', $error);
            }
        });
    }

    // Validated form fields without the media inputs
    public function listingData(): array
    {
        return Arr::except($this->validated(), ['images', 'videos', 'remove_images', 'remove_videos']);
    }

    // Final number of photos on the listing once this request is applied
    protected function photoCount(): int
    {
        return count(Arr::wrap($this->file('images', [])));
    }

    // Final number of videos on the listing once this request is applied
    protected function videoCount(): int
    {
        return count(Arr::wrap($this->file('videos', [])));
    }
}
