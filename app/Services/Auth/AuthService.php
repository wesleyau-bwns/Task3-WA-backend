<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Admin;

class AuthService
{
    protected array $map = [
        'user' => [
            'model' => User::class,
            'guard' => 'user-api',
        ],
        'merchant' => [
            'model' => Merchant::class,
            'guard' => 'merchant-api',
        ],
        'admin' => [
            'model' => Admin::class,
            'guard' => 'admin-api',
        ],
    ];

    public function register(array $data, string $role): array
    {
        if (!isset($this->map[$role])) {
            abort(400, 'Invalid role');
        }

        $modelClass = $this->map[$role]['model'];

        $user = $modelClass::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($role);

        $tokenData = $this->issuePasswordToken(
            $user->email,
            $data['password'],
            $this->map[$role]['guard']
        );

        return [
            'user' => $user,
            'token' => $tokenData,
        ];
    }

    public function login(string $email, string $password, string $role): array
    {
        $guardData = $this->map[$role] ?? null;
        if (!$guardData) {
            abort(400, 'Invalid role');
        }

        $guard = $guardData['guard'];
        $modelClass = $guardData['model'];

        $user = $modelClass::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            abort(401, 'Invalid credentials');
        }

        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized role');
        }

        $tokenData = $this->issuePasswordToken($email, $password, $guard);

        return [
            'user' => $user,
            'token' => $tokenData,
        ];
    }

    public function refresh(string $refreshToken, string $guard): array
    {
        return $this->issueRefreshToken($refreshToken, $guard);
    }

    public function logout($user): void
    {
        $token = $user->token();

        if ($token) {
            $token->revoke();

            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $token->id)
                ->update(['revoked' => true]);
        }
    }

    protected function issuePasswordToken(string $email, string $password, string $guard): array
    {
        return $this->callPassport('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config("services.passport.{$guard}_client_id"),
            'client_secret' => config("services.passport.{$guard}_client_secret"),
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);
    }

    protected function issueRefreshToken(string $refreshToken, string $guard): array
    {
        return $this->callPassport('/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config("services.passport.{$guard}_client_id"),
            'client_secret' => config("services.passport.{$guard}_client_secret"),
            'scope' => '',
        ]);
    }

    protected function callPassport(string $uri, array $payload): array
    {
        $deviceId = request()->header('X-Device-Id');

        $request = request()->create($uri, 'POST', $payload);

        if ($deviceId) {
            $request->headers->set('X-Device-Id', $deviceId);
        }

        $response = app()->handle($request);

        if ($response->getStatusCode() !== 200) {
            Log::error('Passport token request failed', [
                'payload' => $payload,
                'status' => $response->getStatusCode(),
                'body' => $response->getContent(),
            ]);

            abort($response->getStatusCode(), 'OAuth token request failed');
        }

        return json_decode($response->getContent(), true);
    }
}
