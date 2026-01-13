<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function show(): JsonResponse
    {
        $user = Auth::user();
        $profile = $this->userService->getProfile($user);

        return $this->success('Profile retrieved successfully', $profile);
    }

    public function updateProfile(UpdateUserRequest $request): JsonResponse
    {
        $user = Auth::user();
        $updatedUser = $this->userService->updateProfile($user, $request->validated());

        return $this->success('Profile updated successfully', $updatedUser);
    }

    /**
     * Update authenticated user password.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $updatedUser = $this->userService->updatePassword($user, $request->validated());

        return $this->success('Password updated successfully', $updatedUser);
    }
}
