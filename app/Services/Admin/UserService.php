<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function listUsers(): Collection
    {
        return User::all();
    }

    public function getUser(int $id): ?User
    {
        return User::find($id);
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Paginate users with search, filters, and sorting
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function paginateUsers(array $params): LengthAwarePaginator
    {
        $query = User::query();

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
