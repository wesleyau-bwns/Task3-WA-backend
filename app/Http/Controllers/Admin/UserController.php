<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $params = $request->only(['search', 'email_verified', 'sort_by', 'sort_dir', 'per_page']);
        $users = $this->userService->paginateUsers($params);

        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->updateUser($user, $request->validated());
        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);
        return response()->json(null, 204);
    }
}
