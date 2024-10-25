<?php

namespace Modules\Article\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Modules\Article\Service\ArticleService;

class RedisSubscribe extends Command
{
    private readonly ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Redis::subscribe(['news-channel'], function (string $message) {
            $this->articleService->receiveDataFromApi($message);
        });
    }
}
