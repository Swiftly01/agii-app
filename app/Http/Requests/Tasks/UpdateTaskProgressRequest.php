<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskProgressRequest extends FormRequest
{
    // Ownership is checked by the policy in the controller
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['progress' => ['required', 'integer', 'between:0,100']];
    }
}
