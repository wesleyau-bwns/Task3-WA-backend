<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Merchant extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;

    protected string $guard_name = 'merchant-api';

    protected $fillable = [
        'name',
        'email',
        'password',
        'store_name',
        'store_description',
        'status',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];
}
