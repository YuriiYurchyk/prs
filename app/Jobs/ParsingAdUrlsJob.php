<?php

namespace App\Jobs;

use App\Models\Olx\OlxAd;
use App\Models\Olx\OlxSearch;
use App\Services\Parsers\Olx\SearchResultsIterator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParsingAdUrlsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Carbon                $now;
    private OlxSearch             $trackedOlxSearch;

    public function __construct(OlxSearch $olxSearch)
    {
        $this->now                  = Carbon::now();
        $this->trackedOlxSearch     = $olxSearch;
    }

    public function handle()
    {
        $searchResultIterator = new SearchResultsIterator($this->trackedOlxSearch->url);

        do {
            $resultBlockParser = $searchResultIterator->getResultBlockParser();
            $url               = $resultBlockParser->getUrl();
            $ad                = $this->trackedOlxSearch->olxAds()->firstWhere('url', '=', $url);
            if ($ad) {
                $ad->update([
                    'title'          => $resultBlockParser->getTitle(),
                    'price'          => $resultBlockParser->getPrice(),
                    'currency'       => $resultBlockParser->getCurrency(),
                    'publication_at' => $resultBlockParser->getPublicationDate(),
                    'last_active_at' => $this->now,
                ]);

            } else {
                $ad = OlxAd::firstOrNew(['url' => $url]);
                $ad->fill([
                    'title'          => $resultBlockParser->getTitle(),
                    'price'          => $resultBlockParser->getPrice(),
                    'currency'       => $resultBlockParser->getCurrency(),
                    'publication_at' => $resultBlockParser->getPublicationDate(),
                    'last_active_at' => $this->now,
                ]);
                $this->trackedOlxSearch->olxAds()->save($ad);
            }

        } while ($searchResultIterator->next());

    }
}