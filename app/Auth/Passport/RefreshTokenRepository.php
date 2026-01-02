<?php

namespace App\Auth\Passport;

use Laravel\Passport\Bridge\RefreshTokenRepository as PassportRefreshTokenRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefreshTokenRepository extends PassportRefreshTokenRepository
{
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        parent::persistNewRefreshToken($refreshTokenEntity);

        $refreshTokenId = $refreshTokenEntity->getIdentifier();
        $deviceId = request()->header('X-Device-Id');

        // Log::info('passport.refresh_token.persist', [
        //     'refresh_token_id' => $refreshTokenId,
        //     'device_id' => $deviceId
        // ]);

        if ($deviceId) {
            DB::table('oauth_refresh_tokens')
                ->where('id', $refreshTokenId)
                ->update([
                    'device_id' => $deviceId,
                ]);
        }
    }

    public function isRefreshTokenRevoked($refreshTokenId): bool
    {
        $row = DB::table('oauth_refresh_tokens')
            ->where('id', $refreshTokenId)
            ->where('revoked', false)
            ->first();

        // Token missing → revoked
        if (!$row) {
            return true;
        }

        $requestDevice = request()->header('X-Device-Id');

        // No device header or mismatch → invalid_grant
        if (!$requestDevice || $row->device_id !== $requestDevice) {
            Log::warning('refresh_token.device_mismatch', [
                // 'refresh_token_id' => $refreshTokenId,
                'expected_device' => $row->device_id,
                'request_device' => $requestDevice,
            ]);

            throw OAuthServerException::invalidGrant(
                'Refresh token used on another device'
            );
        }

        return parent::isRefreshTokenRevoked($refreshTokenId);
    }
}
