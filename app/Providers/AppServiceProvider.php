<?php

namespace App\Providers;

use App\Models\Olx\OlxAd;
use App\Observers\OlxAdObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        OlxAd::observe(OlxAdObserver::class);
    }
}
