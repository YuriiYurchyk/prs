<?php

namespace App\Console\Commands;

use App\Models\Olx\OlxAd;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'parser:test'; // php artisan parser:test

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $m = OlxAd::first();
        dd($m->trackedOlxSearches);
    }

}
