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

        return response()->json([
            'data' => $profile
        ]);
    }

    public function updateProfile(UpdateUserRequest $request): JsonResponse
    {
        $user = Auth::user();

        $updatedUser = $this->userService->updateProfile(
            $user,
            $request->validated()
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $updatedUser,
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();

        $updatedUser = $this->userService->updatePassword($user, $request->validated());

        return response()->json([
            'message' => 'Password updated successfully',
            'data' => [
                'id' => $updatedUser->id,
                'updated_at' => $updatedUser->updated_at,
            ],
        ]);
    }
}
