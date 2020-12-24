<?php

namespace App\Jobs;

use App\Models\Olx\OlxAd;
use App\Services\Parsers\Olx\AdPageParser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParsingAdDetailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Carbon $now;
    private OlxAd  $olxAd;

    public function __construct(OlxAd $olxAd)
    {
        $this->now   = Carbon::now();
        $this->olxAd = $olxAd;
    }

    public function handle()
    {
        $this->adPageParser = new AdPageParser($this->olxAd->url);

        return true;
    }
}