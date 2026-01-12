<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->authService->register($validated, 'admin');

        return $this->tokenResponseWithCookie($result, 'Admin registered successfully', 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($validated['email'], $validated['password'], 'admin');

        return $this->tokenResponseWithCookie($result, 'Login successful');
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Refresh token missing',
            ], 401);
        }

        $token = $this->authService->refresh($refreshToken, 'admin-api');

        return $this->tokenResponseWithCookie($token, 'Token refreshed successfully');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user('admin-api'));

        return response()->json(['message' => 'Logged out successfully'])->cookie(
            'refresh_token',
            '',
            -1,
            '/',
            null,
            true,
            true,
            false,
            'Lax'
        );
    }

    public function admin(Request $request)
    {
        $admin = $request->user();

        return response()->json([
            'admin' => [
                'name' => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    public function permissions(Request $request)
    {
        $admin = $request->user();

        return response()->json([
            'permissions' => $admin->getAllPermissions()->pluck('name')->toArray(),
        ]);
    }

    /**
     * Send JSON response + refresh token cookie
     */
    protected function tokenResponseWithCookie(array $data, string $message, int $status = 200)
    {
        $response = [
            'message' => $message,
            'access_token' => $data['access_token'] ?? $data['token']['access_token'] ?? null,
            'token_type' => $data['token_type'] ?? $data['token']['token_type'] ?? 'Bearer',
            'expires_in' => $data['expires_in'] ?? $data['token']['expires_in'] ?? null,
        ];

        if (isset($data['admin'])) {
            $admin = $data['admin'];
            $response['admin'] = [
                'name' => $admin->name,
                'email' => $admin->email,
            ];
        }

        return response()->json($response, $status)->cookie(
            'refresh_token',
            $data['refresh_token'] ?? $data['token']['refresh_token'] ?? null,
            60 * 24 * 30,
            '/',
            null,
            true,
            true,
            false,
            'Lax'
        );
    }
}
