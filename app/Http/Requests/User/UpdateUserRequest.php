<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
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
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:50|unique:users,username,' . $this->user()->id,
            'phone_country_code' => 'sometimes|string|max:5',
            'phone_number' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|file|image|mimes:jpg,jpeg,png|max:2048',

            'date_of_birth' => 'sometimes|date|before:today',
            'country' => 'sometimes|string|size:2',

            'address_line1' => 'sometimes|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'sometimes|string|max:100',
            'postal_code' => 'sometimes|string|max:20',

            'language' => 'sometimes|string|max:10',
            'timezone' => 'sometimes|string|max:50',
        ];
    }
}
