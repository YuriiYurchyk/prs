<?php

namespace App\Parsers\Olx;

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
        // todo
    }

    public function getTitle(): string
    {
        $title = $this->crawler->filter('h3')
            ->text();

        return $title;
    }

    public function getPrice(): int
    {
        $priceString = $this->crawler->filter('p.price')
            ->text();

        $chunks   = explode(' ', $priceString);
        $currency = array_pop($chunks);
        $price    = (int)implode('', $chunks);

        return $price;
    }

    public function getCurrency(): int
    {
        $priceRaw = $this->crawler->filter('p.price')
            ->text();

        $chunks   = explode(' ', $priceRaw);
        $currency = array_pop($chunks);

        return $currency;
    }

    public function getPublicationDate()
    {
        $dateRaw = $this->crawler->filter('td.bottom-cell span')
            ->last()->text();

        return $dateRaw;
    }

    public function getLocation(): string
    {
        $location = $this->crawler->filter('td.bottom-cell span')
            ->first()->text();

        return $location;
    }
}