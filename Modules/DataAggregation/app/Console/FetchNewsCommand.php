<?php

namespace Modules\DataAggregation\Console;

use Illuminate\Console\Command;
use Modules\DataAggregation\Enum\NewsSourcesEnum;
use Modules\DataAggregation\Jobs\FetchNewsJob;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //        FetchNewsJob::dispatch(NewsSourcesEnum::NEW_YORK_TIMES->value);
        //        FetchNewsJob::dispatch(NewsSourcesEnum::GUARDIAN->value);
        FetchNewsJob::dispatch(NewsSourcesEnum::NEWS_API->value);
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
