<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->authService->register($validated);

        return $this->tokenResponseWithCookie(
            'User registered successfully',
            $result['token'],
            $result['user'],
            201
        );
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$request->header('X-Device-Id')) {
            abort(400, 'Device ID missing');
        }

        $result = $this->authService->login(
            $validated['email'],
            $validated['password']
        );

        return $this->tokenResponseWithCookie(
            'Login successful',
            $result['token'],
            $result['user']
        );
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Refresh token missing',
            ], 401);
        }

        $token = $this->authService->refresh($refreshToken);

        return $this->tokenResponseWithCookie(
            'Token refreshed successfully',
            $token
        );
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()
            ->json([
                'message' => 'Successfully logged out',
            ])
            ->cookie(
                'refresh_token',
                '',
                -1,
                '/',
            );
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(), 
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'merchant_id' => $user->merchant_id ?? null,
            ],
        ]);
    }

    /**
     * Build access-token response + refresh-token cookie
     */
    protected function tokenResponseWithCookie(
        string $message,
        array $token,
        $user = null,
        int $status = 200
    ) {
        $response = response()->json([
            'message' => $message,
            'token_type' => $token['token_type'],
            'access_token' => $token['access_token'],
            'expires_in' => $token['expires_in'],
            ...($user ? ['user' => $this->userResponse($user)] : []),
        ], $status);

        return $response->cookie(
            'refresh_token',
            $token['refresh_token'],
            60 * 24 * 30,          // 30 days
            '/',
            null,
            true,                 // Secure
            true,                 // HttpOnly
            false,
            'Lax'
        );
    }

    protected function userResponse($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
