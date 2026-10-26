<?php

namespace App\Http\Requests;

use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class PhoneRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Normalizing first lets the unique rule compare against the stored E.164 format
    protected function prepareForValidation(): void
    {
        $phone = (string) $this->input('phone');

        $this->merge([
            'phone' => PhoneNumber::normalize($phone, config('phone_verification.default_country_code')) ?? $phone,
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{7,14}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', Rule::in(config('phone_verification.allowed_user_types'))],
            'referral_code' => ['nullable', 'string', 'max:20'],
            'agree_terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number, for example 0803 123 4567.',
            'phone.unique' => 'This phone number is already registered.',
            'agree_terms.accepted' => 'You must accept the terms and conditions.',
        ];
    }
}
