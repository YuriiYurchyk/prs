<?php

namespace App\Services\Parsers\Olx;

use App\Services\WebPages;
use Symfony\Component\DomCrawler\Crawler;

class SearchPaginator
{
    private string $firstSearchPageUrl;

    private int $currentSearchPageKey;

    private Crawler $currentSearchPageCrawler;

    private array $allPageUrls;

    private int $lastPageNumber = 0;

    public array $adUrls;

    public function __construct(string $firstSearchPageUrl)
    {
        $this->firstSearchPageUrl = trim($firstSearchPageUrl, '/');

        $this->currentSearchPageCrawler = $this->makeCrawler($this->firstSearchPageUrl);
        $this->currentSearchPageKey     = 0;

        $this->lastPageNumber = $this->getLastSearchPageNumber();
        $this->allPageUrls    = $this->getSearchUrls();

        $this->adUrls = $this->parseResultUrls();
    }

    public function parseResultUrls(): array
    {
        $ads = $this->currentSearchPageCrawler
            ->filter('section.container table#offers_table tr.wrap a.link');

        $adUrls = $ads->each(function (Crawler $link) {
            return $link->attr('href');
        });

        $adUrls = array_map(function ($url) {
            return $this->clearUrl($url);
        }, $adUrls);

        $adUrls = array_unique($adUrls);

        return $adUrls;
    }

    public function nextPage(): bool
    {
        $currentSearchPageNumber = $this->currentSearchPageKey + 1;
        if ($currentSearchPageNumber === $this->lastPageNumber) {
            return false;
        }

        $this->currentSearchPageKey++;
        $pageUrl                        = $this->allPageUrls[$this->currentSearchPageKey];
        $this->currentSearchPageCrawler = $this->makeCrawler($pageUrl);

        return true;
    }

    public function parseResultNodeCrawlers(): array
    {
        $resultNodes = $this->currentSearchPageCrawler
            ->filter('table#offers_table tr.wrap tbody');

        $resultNodes = $resultNodes->each(function (Crawler $node) {
            return $node;
        });

        return $resultNodes;
    }

    private function getSearchUrls(): array
    {
        $searchUrlTemplate = $this->getSearchUrlTemplate();
        $searchUrls        = [$this->firstSearchPageUrl];

        for ($i = 2; $i <= $this->lastPageNumber; $i++) {
            $searchUrls[] = $searchUrlTemplate . $i;
        }

        return $searchUrls;
    }

    private function getSearchUrlTemplate(): string
    {
        if (strpos($this->firstSearchPageUrl, 'search')) {
            $templateURL = $this->firstSearchPageUrl . '&page=';
        } else {
            $templateURL = $this->firstSearchPageUrl . '/?page=';
        };

        return $templateURL;
    }

    private function getLastSearchPageNumber(): int
    {
        $lastPaginateItem = $this->currentSearchPageCrawler
            ->filter('section.container div.pager span.item:nth-last-of-type(2)');

        $arrayLastPageNumber = $lastPaginateItem->each(function (Crawler $link) {
            return intval($link->text());
        });

        $this->lastPageNumber = max(reset($arrayLastPageNumber), 1);

        return $this->lastPageNumber;
    }

    private function makeCrawler(string $url): Crawler
    {
        $html = WebPages::getPage($url);

        return new Crawler($html);
    }

    private function clearUrl(string $url): string
    {
//        $url = preg_replace('|(\?sd=.{12}(;promoted)?)? (\#.{10}$)?|xs', '', $url);

        $url = explode('.html', $url)[0];
        $url .= '.html';

        return $url;
    }
}