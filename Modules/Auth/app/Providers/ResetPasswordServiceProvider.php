<?php

namespace Modules\Auth\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Models\User;

class ResetPasswordServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return 'https://example.com/reset-password?token='.$token;
        });
    }
}
