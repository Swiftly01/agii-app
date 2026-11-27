<?php

namespace App\Http\Requests;

use App\Enums\EducationLevel;
use App\Support\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'years_of_experience' => ['required', 'integer', 'between:0,60'],
            'education_level' => ['required', Rule::enum(EducationLevel::class)],
            // Certificate is optional, kept to common document and photo formats up to 5MB
            'certificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'agree_terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number, for example 0803 123 4567.',
            'phone.unique' => 'This phone number is already registered.',
            'years_of_experience.between' => 'Years of experience must be between 0 and 60.',
            'education_level.required' => 'Select your highest level of education.',
            'certificate.mimes' => 'The certificate must be a PDF, JPG or PNG file.',
            'certificate.max' => 'The certificate must not be larger than 5MB.',
            'agree_terms.accepted' => 'You must accept the terms and conditions.',
        ];
    }
}
