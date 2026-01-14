<?php

namespace App\Policies\User;

use App\Models\User\BankAccount;
use App\Models\User;

class BankAccountPolicy
{
    public function viewAny(User $user): bool
    {
        // Any authenticated user can see their own bank accounts
        return true;
    }

    public function view(User $user, BankAccount $bankAccount): bool
    {
        return $bankAccount->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Any authenticated user can see their own bank accounts
        return true;
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return $bankAccount->user_id === $user->id;
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return $bankAccount->user_id === $user->id;
    }
}
