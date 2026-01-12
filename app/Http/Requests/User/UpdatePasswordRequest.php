<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Traits\PasswordRules;

class UpdatePasswordRequest extends FormRequest
{
    use PasswordRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => $this->requiredPasswordRules(),
        ];
    }

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
