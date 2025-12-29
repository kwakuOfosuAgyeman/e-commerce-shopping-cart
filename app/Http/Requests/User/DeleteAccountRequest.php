<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteAccountRequest extends FormRequest
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
        $authUser = Auth::user();

        // Password is only required when deleting own account
        if ($authUser && $authUser->id == $userId) {
            return [
                'password' => 'required|string',
            ];
        }

        return [];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Please enter your password to confirm account deletion.',
        ];
    }
}
