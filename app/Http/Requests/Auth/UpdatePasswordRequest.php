<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users can update their password
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => [
                'required',
                'string',
                'min:8',              // Minimum length
                'confirmed',          // Requires `new_password_confirmation` to match
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[0-9]/',      // At least one number
                'regex:/[@$!%*#?&]/', // At least one special character
            ],
        ];
    }

    /**
     * Custom validation after rules run
     */
    protected function passedValidation()
    {
        $user = $this->user();

        if (!Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
        ];
    }
}
