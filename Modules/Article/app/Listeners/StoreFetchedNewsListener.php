<?php

namespace Modules\Article\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Article\Service\ArticleService;
use Modules\DataAggregation\Events\NewsFetchedEvent;
use Throwable;

class StoreFetchedNewsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly ArticleService $articleService
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsFetchedEvent $event): void
    {
        $this->articleService->receiveDataFromApi($event->newsData);
    }

    /**
     * Handle a job failure.
     */
    public function failed(NewsFetchedEvent $event, Throwable $exception): void
    {
        Log::info('Failed Fetch', [$exception->getMessage()]);
    }
}
