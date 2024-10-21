<?php

namespace Modules\Auth\Actions;

use Auth;

readonly class LogoutAction
{
    public function handle(): void
    {
        Auth::user()?->tokens()->delete();
    }
}
