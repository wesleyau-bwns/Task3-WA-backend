<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Contact
            $table->string('username', 50)->unique()->nullable()->after('email');
            $table->string('phone_country_code', 5)->nullable()->after('username');
            $table->string('phone_number', 20)->nullable()->after('phone_country_code');
            $table->string('avatar')->nullable()->after('phone_number');

            // Personal
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->char('country', 2)->nullable()->after('date_of_birth');

            // Address
            $table->string('address_line1')->nullable()->after('country');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city', 100)->nullable()->after('address_line2');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('state');

            // Preferences
            $table->char('default_currency', 3)->default('USD')->after('postal_code');
            $table->string('language', 10)->default('en')->after('default_currency');
            $table->string('timezone', 50)->default('UTC')->after('language');

            // KYC
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->default('pending')->after('timezone');
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_status');

            // Security & status
            $table->boolean('two_factor_enabled')->default(false)->after('kyc_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_enabled');
            $table->enum('account_status', ['active', 'suspended', 'closed'])->default('active')->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'phone_country_code',
                'phone_number',
                'avatar',
                'date_of_birth',
                'country',
                'address_line1',
                'address_line2',
                'city',
                'state',
                'postal_code',
                'default_currency',
                'language',
                'timezone',
                'kyc_status',
                'kyc_verified_at',
                'two_factor_enabled',
                'last_login_at',
                'account_status',
            ]);
        });
    }
};
