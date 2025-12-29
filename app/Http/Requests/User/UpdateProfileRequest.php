<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('userId');

        return [
            'name' => 'sometimes|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'sometimes|email:rfc,dns|unique:users,email,' . $userId . '|max:255',
            'phone' => 'sometimes|nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'current_password' => 'required_with:password',
            'password' => [
                'sometimes',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Name can only contain letters and spaces.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'current_password.required_with' => 'Current password is required when changing password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.regex' => 'Please enter a valid phone number.',
        ];
    }
}
