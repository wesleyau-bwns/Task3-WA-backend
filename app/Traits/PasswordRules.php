<?php

namespace App\Traits;

trait PasswordRules
{
    /**
     * Password rules for optional password (nullable)
     */
    public function optionalPasswordRules(): array
    {
        return [
            'nullable',
            'string',
            'min:8',
            'confirmed',
            'regex:/[a-z]/',      // At least one lowercase
            'regex:/[A-Z]/',      // At least one uppercase
            'regex:/[0-9]/',      // At least one number
            'regex:/[@$!%*#?&]/', // At least one special character
        ];
    }

    /**
     * Password rules for required password
     */
    public function requiredPasswordRules(): array
    {
        return [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[a-z]/',      // At least one lowercase
            'regex:/[A-Z]/',      // At least one uppercase
            'regex:/[0-9]/',      // At least one number
            'regex:/[@$!%*#?&]/', // At least one special character
        ];
    }
}
