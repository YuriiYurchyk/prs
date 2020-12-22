<?php

namespace App\Jobs;

use App\Models\OlxAd;
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
    private Carbon           $now;

    public function __construct(TrackedOlxSearch $url)
    {
        $this->trackedOlxSearch = $url;
        $this->now              = Carbon::now();
    }

    public function handle()
    {
        $searchResultIterator = new SearchResultsIterator($this->trackedOlxSearch->url);

        do {
            $resultBlockParser = $searchResultIterator->getResultBlockParser();
            $url               = $resultBlockParser->getUrl();
            $ad                = OlxAd::firstOrNewByUrl($url);

            $ad->fill([
                'url'            => $url,
                'title'          => $resultBlockParser->getTitle(),
                'price'          => $resultBlockParser->getPrice(),
                'currency'       => $resultBlockParser->getCurrency(),
                'publication_at' => $resultBlockParser->getPublicationDate(),
                'last_active_at' => $this->now,
            ]);
            $ad->save();

        } while ($searchResultIterator->next());

    }
}