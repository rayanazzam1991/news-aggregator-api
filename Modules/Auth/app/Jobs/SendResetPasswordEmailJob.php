<?php

namespace Modules\Auth\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;

class SendResetPasswordEmailJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly string $email

    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Password::sendResetLink([
            'email' => $this->email,
        ]);

    }
}
