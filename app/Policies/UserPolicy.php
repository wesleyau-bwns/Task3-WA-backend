<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users (e.g. /users).
     * Admin only.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can view a specific user.
     * Admin → anyone
     * User → themselves only
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') || $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can create users.
     * Admin only.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Determine whether the user can update a user.
     * Admin → anyone
     * User → themselves only
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') || $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete a user.
     * Admin only.
     */
    public function delete(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Restore user (if using soft deletes).
     * Admin only.
     */
    public function restore(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin');
    }

    /**
     * Permanently delete user.
     * Admin only.
     */
    public function forceDelete(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin');
    }
}
