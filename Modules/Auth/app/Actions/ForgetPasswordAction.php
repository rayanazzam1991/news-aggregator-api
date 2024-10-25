<?php

namespace Modules\Auth\Actions;

use Modules\Auth\Jobs\SendResetPasswordEmailJob;

readonly class ForgetPasswordAction
{
    public function handle(string $email): void
    {
        // TODO i should use this defer to async sending email it's new method in Laravel,
        //   but i could not make it run during integration test so i comment it for now, i can use job instead
        //  but i choose this way to show this very new method.

        //        defer(function () use ($email) {
        $this->sendResetLink($email);
        //        });

    }

    private function sendResetLink(string $email): void
    {
        SendResetPasswordEmailJob::dispatch($email);
    }
}
