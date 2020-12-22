<?php

namespace App\Jobs;

use App\Models\TrackedOlxSearch;
use App\Parsers\Olx\SearchResultsIterator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParsingAdUrlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private TrackedOlxSearch $trackedOlxSearch;
    private Carbon           $nowDate;

    public function __construct(TrackedOlxSearch $url)
    {
        $this->trackedOlxSearch = $url;
        $this->nowDate          = Carbon::now();
    }

    public function handle()
    {
        return true;

        if (!$this->trackedOlxSearch->isNeedTrack()) {
            return true;
        }

        $searchResultIterator = new SearchResultsIterator($this->trackedOlxSearch->url);

        $dates = [];
        do {
//            $resultPageParser  = $searchResultIterator->getResultPageParser();
            $resultBlockParser = $searchResultIterator->getResultBlockParser();
            $dates[]           = $resultBlockParser->getLocation();

//            break;
        } while ($searchResultIterator->next());

//        if ($trackedSearchUrl->isAllPagesTracked()) {
//            // тут не проставляєм not found
//        }

    }
}