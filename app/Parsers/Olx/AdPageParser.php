<?php

namespace App\Parsers\Olx;

use App\Services\WebPages;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class AdPageParser
{
    protected Crawler $crawler;

    public function __construct(string $url)
    {
        $crawler = $this->getCrawler($url);
        $this->crawler = $crawler;
    }

    public function getTitle()
    {
        $value = $this->crawler
            ->filter('div.content div.offer-titlebox h1')
            ->text();

        return $value;
    }

    public function getPrice(): array
    {
        $value = $this->crawler
            ->filter('div.content div.offer-titlebox div.pricelabel')
            ->text();

        $str   = explode(' ', $value);
        $price = intval(implode($str));

        return $price;
    }

    public function getCurrency(): array
    {
        $value = $this->crawler
            ->filter('div.content div.offer-titlebox div.pricelabel')
            ->text();

        $str      = explode(' ', $value);
        $currency = array_pop($str);

        return $currency;
    }

    public function getPublicationDate(): Carbon
    {
        $date = $this->crawler
            ->filter('div.content div.offer-bottombar em')
            ->text();

        $date       = mb_substr($date, 2);
        $date       = $this->monthStringToMonthNumber($date);
        $carbonDate = Carbon::createFromFormat('H:i, d m Y', $date);

        return $carbonDate;
    }

    public function getDescription()
    {
        $value = $this->crawler
            ->filter('div.content div#textContent')
            ->text();

//        для удаления лишних пропусков
//        preg_replace('|<br />(?:\s*?)<br />|iu','<br />', $value);

        return $value;
    }

    private function getCrawler(string $url): Crawler
    {
        $html = WebPages::getPage($url);

        return new Crawler($html);
    }

    protected function monthStringToMonthNumber(string $date)
    {
        $months = [
            'января'    => '01',
            'февраля'   => '02',
            'марта'     => '03',
            'апреля'    => '04',
            'мая'       => '05',
            'июня'      => '06',
            'июля'      => '07',
            'августа'   => '08',
            'сентября'  => '09',
            'октября'   => '10',
            'ноября'    => '11',
            'декабря'   => '12',
            'січня'     => '01',
            'лютого'    => '02',
            'березня'   => '03',
            'квітня'    => '04',
            'травня'    => '05',
            'червня'    => '06',
            'липня'     => '07',
            'серпня'    => '08',
            'вересня'   => '09',
            'жовтня'    => '10',
            'листопада' => '11',
            'грудня'    => '12'
        ];

        $date = str_replace(array_keys($months), array_values($months), $date);

        return $date;
    }
}