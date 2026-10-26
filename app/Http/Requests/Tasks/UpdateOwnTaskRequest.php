<?php

namespace App\Http\Requests\Tasks;

class UpdateOwnTaskRequest extends StoreOwnTaskRequest
{
    // Ownership is checked by the policy in the controller
    public function authorize(): bool
    {
        return true;
    }

    // Same rules as creating, except an existing deadline may already be past
    public function rules(): array
    {
        return array_merge(parent::rules(), ['deadline' => ['required', 'date']]);
    }
}