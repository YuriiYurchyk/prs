<?php

namespace App\Parsers\Olx;

use App\Jobs\ParsingAdUrlsJob;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class SearchResultBlockParser
{
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getUrl(): string
    {
        $urlRaw  = $this->crawler->filter('h3 a')
            ->attr('href');
        $baseUrl = strtok($urlRaw, '?');

        return $baseUrl;
    }

    public function getTitle(): string
    {
        $title = $this->crawler->filter('h3')
            ->text();

        return $title;
    }

    public function getPrice(): ?int
    {
        $priceNode = $this->crawler->filter('p.price');

        if (!$priceNode->count()) {
            return null;
        }

        $priceString = $priceNode->text();
        $chunks      = explode(' ', $priceString);
        $currency    = array_pop($chunks);
        $price       = (int)implode('', $chunks);

        return $price;
    }

    public function getCurrency(): ?string
    {
        $priceNode = $this->crawler->filter('p.price');

        if (!$priceNode->count()) {
            return null;
        }

        $priceString = $priceNode->text();
        $chunks      = explode(' ', $priceString);
        $currency    = array_pop($chunks);
        $price       = (int)implode('', $chunks);

        return $currency;
    }

    public function getPublicationDate(): Carbon
    {
        $dateRaw = $this->crawler->filter('td.bottom-cell span')
            ->last()->text();

        if ($this->isToday($dateRaw)) {
            $time = $this->getTodayTime($dateRaw);
            $date = Carbon::now()->setTimeFromTimeString($time);
        } elseif ($this->isYesterday($dateRaw)) {
            $time = $this->getYesterdayTime($dateRaw);
            $date = Carbon::yesterday()->setTimeFromTimeString($time);
        }

        return $date;
    }

    public function getLocation(): string
    {
        $location = $this->crawler->filter('td.bottom-cell span')
            ->first()->text();

        return $location;
    }

    protected function isToday($dateRaw): bool
    {
        $today = str_contains($dateRaw, 'Сегодня') ||
            str_contains($dateRaw, 'Сьогодні');

        return $today;
    }

    protected function isYesterday($dateRaw): bool
    {
        $yesterday = str_contains($dateRaw, 'Вчера') ||
            str_contains($dateRaw, 'Вчора');

        return $yesterday;
    }

    protected function getTodayTime($dateRaw): string
    {
        $todayString = ['Сегодня', 'Сьогодні'];
        $time        = str_replace($todayString, '', $dateRaw);

        return $time;
    }

    protected function getYesterdayTime($dateRaw): string
    {
        $todayString = ['Вчера', 'Вчора'];
        $time        = str_replace($todayString, '', $dateRaw);

        return $time;
    }
}