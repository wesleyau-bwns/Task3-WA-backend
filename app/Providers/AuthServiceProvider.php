<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\User\BankAccount;
use App\Policies\User\BankAccountPolicy;

use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        BankAccount::class => BankAccountPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        Passport::enablePasswordGrant();

        // Token expiration settings
        Passport::tokensExpireIn(now()->addMinutes(config('passport.access_token_expire_minutes')));
        Passport::refreshTokensExpireIn(now()->addDays(config('passport.refresh_token_expire_days')));
        Passport::personalAccessTokensExpireIn(now()->addMonths(config('passport.personal_access_token_expire_months')));
    }
}
