<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $tokenData = $this->issuePasswordToken(
            $user->email,
            $data['password']
        );

        return [
            'user' => $user,
            'token' => $tokenData,
        ];
    }

    public function login(string $email, string $password): array
    {
        if (!Auth::attempt(compact('email', 'password'))) {
            abort(401, 'Invalid credentials');
        }

        $user = User::where('email', $email)->firstOrFail();

        $tokenData = $this->issuePasswordToken($email, $password);

        return [
            'user' => $user,
            'token' => $tokenData,
        ];
    }

    public function refresh(string $refreshToken): array
    {
        return $this->issueRefreshToken($refreshToken);
    }

    public function logout(User $user): void
    {
        $token = $user->token();

        if (!$token) {
            return;
        }

        $token->revoke();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update(['revoked' => true]);
    }

    protected function issuePasswordToken(string $email, string $password): array
    {
        return $this->callPassport('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);
    }

    protected function issueRefreshToken(string $refreshToken): array
    {
        return $this->callPassport('/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => config('services.passport.password_client_id'),
            'client_secret' => config('services.passport.password_client_secret'),
            'scope' => '',
        ]);
    }

    protected function callPassport(string $uri, array $payload): array
    {
        $deviceId = request()->header('X-Device-Id');

        $request = Request::create($uri, 'POST', $payload);

        if ($deviceId) {
            $request->headers->set('X-Device-Id', $deviceId);
        }

        $response = app()->handle($request);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            abort(
                $response->getStatusCode(),
                'OAuth token request failed'
            );
        }

        return json_decode($response->getContent(), true);
    }
}
