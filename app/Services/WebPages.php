<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Cache;

class WebPages
{
    private const PAGE_NOT_FOUND = 'PAGE_NOT_FOUND';
    private static $connectionAttempts;

    public static function getPage(string $url, bool $useCache = true): ?string
    {
        if (!$useCache) {
            $html = self::downloadHtml($url);

            return $html;
        }

        $html = Cache::get($url);
        if ($html == self::PAGE_NOT_FOUND) {
            $html = '';

        } elseif (!$html) {
            $html = self::downloadHtml($url);

//            Cache::put('http://olx.ua/', $body, Carbon::now()->addMinutes(2));
            Cache::put($url, $html, $expire = null);
        }

//        file_put_contents('a.html', $html);

        return $html;
    }

    public static function downloadHtml(string $url)
    {
        $client        = new Client();
        $redirectedUrl = '';
        $res           = $client->request('GET', $url, [
            'on_stats' => function (TransferStats $stats) use (&$redirectedUrl) {
                $redirectedUrl = (string)$stats->getEffectiveUri();
            },
            //            'proxy'   => self::getRandomProxy(),
            //            'headers' => [ // тут треба проставляти усі заголовки, імітуючи браузер
            //                           'User-Agent' => self::getRandomUserAgent(),
            //            ],
        ]);

        if (self::isPageNotFound($url, $redirectedUrl)) {
            return self::PAGE_NOT_FOUND;
        }

        $pageBody = (string)$res->getBody();

        return $pageBody;
    }

    private static function isPageNotFound($url, $redirectedUrl)
    {
        return trim($url, '/') != trim($redirectedUrl, '/');
    }
}