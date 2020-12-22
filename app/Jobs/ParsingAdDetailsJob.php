<?php

namespace App\Jobs;

use App\Models\TrackedOlxSearch;
use App\Parsers\Olx\SearchResultsIterator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParsingAdDetailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(TrackedOlxSearch $trackedOlxSearch)
    {

    }

    public function handle()
    {

    }
}