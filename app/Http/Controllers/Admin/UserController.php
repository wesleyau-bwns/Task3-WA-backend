<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request): JsonResponse
    {
        $params = $request->only([
            'search',
            'email_verified',
            'sort_by',
            'sort_dir',
            'per_page'
        ]);

        $users = $this->userService->paginateUsers($params);

        return $this->success(
            'Users retrieved successfully',
            $users->items(),
            meta: [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        );
    }

    public function show(User $user): JsonResponse
    {
        return $this->success('User retrieved successfully', $user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());

        return $this->success('User updated successfully', $updatedUser);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);

        return $this->success('User deleted successfully');
    }
}
