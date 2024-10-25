<?php

namespace App\Providers;

use Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Modules\Article\Listeners\StoreFetchedNewsListener;
use Modules\DataAggregation\Events\NewsFetchedEvent;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            events: NewsFetchedEvent::class,
            listener: StoreFetchedNewsListener::class
        );

        RateLimiter::for('api', function (Request $request) {
            Log::info("here");
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
