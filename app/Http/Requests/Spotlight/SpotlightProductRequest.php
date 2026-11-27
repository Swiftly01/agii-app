<?php

namespace App\Http\Requests\Spotlight;

use Illuminate\Foundation\Http\FormRequest;

class SpotlightProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Ownership and status are business rules, SpotlightService enforces them
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer'],
        ];
    }
}
