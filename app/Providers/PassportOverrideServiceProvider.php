<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository as PassportRefreshTokenRepository;
use App\Auth\Passport\RefreshTokenRepository as AppRefreshTokenRepository;

class PassportOverrideServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            PassportRefreshTokenRepository::class,
            AppRefreshTokenRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
