<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyPhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'digits:'.config('phone_verification.code_length')],
        ];
    }
}
