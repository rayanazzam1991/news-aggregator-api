<?php

use Illuminate\Support\Carbon;
use Modules\Auth\Models\User;
use function Pest\Laravel\{postJson,actingAs};

describe('Test Api Rate limit',function (){

    beforeEach(function (){
        $this->user = User::factory()->create();
    });
    it('allows up to 60 requests per minute', function () {
        // Simulate 60 requests from the same IP or user
        $response = null;
        for ($i = 0; $i < 60; $i++) {
            $response = actingAs($this->user)->postJson('/api/v1/articles/list'); // Replace with your actual endpoint
            $response->assertStatus(200);
        }

        // The 61st request should be rate-limited
        $response = actingAs($this->user)->postJson('/api/v1/articles/list');
        $response->assertStatus(429); // 429 Too Many Requests
        expect($response->json('message'))->toContain('Too Many Attempts.');
    });

    it('resets the rate limit after 1 minute', function () {
        // Simulate 60 requests from the same IP or user
        for ($i = 0; $i < 60; $i++) {
            actingAs($this->user)->postJson('/api/v1/articles/list')->assertStatus(200);
        }

        // The 61st request should fail due to rate limiting
        $response = actingAs($this->user)->postJson('/api/v1/articles/list');
        $response->assertStatus(429);

        // Move time forward by 1 minute to reset the rate limiter
        Carbon::setTestNow(now()->addMinute());

        // Clear the rate limiter for this user/IP to simulate the reset
        RateLimiter::clear($this->user->id); // Clear specifically for this user

        // The next request should now be allowed again
        $response = actingAs($this->user)->postJson('/api/v1/articles/list');
        $response->assertStatus(200);
    });
});
