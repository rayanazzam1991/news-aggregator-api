<?php

namespace Modules\DataAggregation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;
use Log;
use Modules\DataAggregation\Contracts\FetchNewsServiceInterface;
use Modules\DataAggregation\Events\NewsFetchedEvent;
use Modules\DataAggregation\Service\FetchNTimesNewsService;

class FetchNewsJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $newsSource;
    private readonly FetchNTimesNewsService $fetchNewsService;

    /**
     * Create a new job instance.
     */
    public function __construct($newsSource

    )
    {
        $this->newsSource = $newsSource;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $newsService = app(FetchNewsServiceInterface::class, ['source' => $this->newsSource]);

        $newsData = $newsService->fetch();

        NewsFetchedEvent::dispatch(json_encode($newsData));

    }
}
