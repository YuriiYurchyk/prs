<?php

namespace App\Console\Commands;

use App\Jobs\ParsingAdUrlsJob;
use App\Models\Olx\OlxSearch;
use Illuminate\Console\Command;

class SearchResultsTracker extends Command
{
    protected $signature = 'parser:search-track'; // php artisan parser:search-track

    protected $description = 'Saves new, tracks the dynamics of changes in parameters of previously saved search results';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $trackedSearchUrls = OlxSearch::active()->get();

        /** @var OlxSearch $trackedSearchUrl */
        foreach ($trackedSearchUrls as $trackedSearchUrl) {

            if ($trackedSearchUrl->isNeedTrack()) {
                ParsingAdUrlsJob::dispatch($trackedSearchUrl);
            }

        }

        return true;
    }

}
