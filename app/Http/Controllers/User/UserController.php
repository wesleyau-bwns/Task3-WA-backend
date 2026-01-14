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
    public function __construct(protected UserService $userService) {}

    public function show(): JsonResponse
    {
        $user = Auth::user();
        $profile = $this->userService->getProfile($user);

        return $this->success('Profile retrieved successfully', $profile);
    }

    public function updateProfile(UpdateUserRequest $request): JsonResponse
    {
        $user = Auth::user();

        // Get all validated fields except files
        $data = $request->validated();

        // Handle avatar file separately
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');
            $data['avatar'] = $avatarFile;
        }

        $updatedUser = $this->userService->updateProfile($user, $data);

        return $this->success('Profile updated successfully', $updatedUser);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $updatedUser = $this->userService->updatePassword($user, $request->validated());

        return $this->success('Password updated successfully', $updatedUser);
    }
}
