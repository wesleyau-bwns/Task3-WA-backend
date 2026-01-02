<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Order;
use App\Models\User;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;

use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        Passport::enablePasswordGrant();

        // Token expiration settings
        Passport::tokensExpireIn(now()->addMinutes(10));
        Passport::refreshTokensExpireIn(now()->addDays(30)); // Refresh token expires in 30 days
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
