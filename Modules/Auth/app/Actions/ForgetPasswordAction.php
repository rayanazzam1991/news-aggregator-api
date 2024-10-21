<?php

namespace Modules\Auth\Actions;

use Illuminate\Support\Facades\Password;
use Log;

readonly class ForgetPasswordAction
{
    public function handle(string $email): void
    {
        //        defer(function () use ($email) {
        $status = $this->sendResetLink($email);
        Log::info('Reset Password Email Status:', [$status]);
        //        });
    }

    private function sendResetLink(string $email): string
    {
        return Password::sendResetLink([
            'email' => $email,
        ]);
    }
}
