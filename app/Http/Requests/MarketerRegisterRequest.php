<?php

namespace App\Http\Requests;

use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

final class MarketerRegisterRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{7,14}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'state' => ['required', 'string', 'max:255'],
            'local_government' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'application_note' => ['nullable', 'string', 'max:1000'],
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
