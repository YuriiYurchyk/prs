<?php

namespace App\Services\Parsers\Olx;

class SearchResultsIterator
{
    private SearchPaginator $searchPaginator;

    private int   $currentResultKey = 0;
    private array $searchResultUrls;
    private array $searchResultNodeCrawlers;

    public function __construct(string $firstSearchPageUrl)
    {
        $this->searchPaginator          = new SearchPaginator($firstSearchPageUrl);
        $this->searchResultUrls         = $this->searchPaginator->parseResultUrls();
        $this->searchResultNodeCrawlers = $this->searchPaginator->parseResultNodeCrawlers();
    }

    public function next(): bool
    {
        if ($this->isCurrentResultNotLast()) {
            $this->currentResultKey++;
            return true;
        }

        $searchPageSwitched = $this->searchPaginator->nextPage();
        if (!$searchPageSwitched) {
            return false;
        }

        $this->currentResultKey         = 0;
        $this->searchResultUrls         = $this->searchPaginator->parseResultUrls();
        $this->searchResultNodeCrawlers = $this->searchPaginator->parseResultNodeCrawlers();
        return true;
    }

    public function getResultPageParser(): AdPageParser
    {
        $url    = $this->getUrl();
        $parser = new AdPageParser($url);

        return $parser;
    }

    public function getResultBlockParser(): SearchResultBlockParser
    {
        $node   = $this->searchResultNodeCrawlers[$this->currentResultKey];
        $parser = new SearchResultBlockParser($node);

        return $parser;
    }

    public function getUrl(): string
    {
        return $this->searchResultUrls[$this->currentResultKey];
    }

    private function isCurrentResultNotLast(): bool
    {
        $resultAmount        = count($this->searchResultUrls);
        $currentResultNumber = $this->currentResultKey + 1;
        $last                = $currentResultNumber === $resultAmount;

        return !$last;
    }

}