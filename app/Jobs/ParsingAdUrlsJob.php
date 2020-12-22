<?php

namespace App\Jobs;

use App\Models\Olx\OlxAd;
use App\Models\Olx\OlxSearch;
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

    private OlxSearch $trackedOlxSearch;
    private Carbon    $now;

    public function __construct(OlxSearch $url)
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
            $ad                = $this->trackedOlxSearch->olxAds()->firstWhere('url', '=', $url);
            if ($ad) {
                $ad->update([
                    'title'          => $resultBlockParser->getTitle(),
                    'price'          => $resultBlockParser->getPrice(),
                    'currency'       => $resultBlockParser->getCurrency(),
                    'publication_at' => $resultBlockParser->getPublicationDate(),
                    'last_active_at' => $this->now,
                ]);
            }

            if (!$ad) {
                $ad = OlxAd::firstOrNew(['url' => $url]);
                $ad->fill([
                    // todo перевіряти зміни назви оголошення, ціни, валюти, дати публікації
                    //  і уже потім при парсингу оголошення перевіряти дескріпшен

                    /*
                     * як варіант можна зробити json колонки в базі
                     * і в джейсоні зберігати дату зміни і значення, на яке змінились дані
                     */
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