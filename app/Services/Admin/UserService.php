<?php

namespace App\Services\Admin;

use App\Models\User;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function listUsers(): Collection
    {
        return User::where('account_status', 'active')->get();
    }

    public function getUser(int $id): ?User
    {
        return User::find($id);
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password'])) {
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }
        }

        $original = $user->getOriginal();
        $user->update($data);
        $changes = $user->getChanges();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties([
                'old' => array_intersect_key($original, $changes), // only the fields that changed
                'new' => $changes
            ])
            ->log('updated user');

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        $user->account_status = "closed";
        $user->save();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['name' => $user->name, 'email' => $user->email])
            ->log('closed user account');

        return true;
    }

    /**
     * Paginate users with search, filters, and sorting
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function paginateUsers(array $params): LengthAwarePaginator
    {
        $query = User::where('account_status', 'active');

        // Search 
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filters
        if (isset($params['email_verified'])) {
            if ($params['email_verified']) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Sorting
        $sortBy = $params['sort_by'] ?? 'created_at';
        $sortDir = $params['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        $perPage = $params['per_page'] ?? 10;

        return $query->paginate($perPage);
    }
}
