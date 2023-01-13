<?php

namespace App\Providers;

use App\Adapter\ElasticSearchAdapter;
use App\Interfaces\ElasticSearchClientInterface;
use Illuminate\Support\ServiceProvider;

class ElasticSearchServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ElasticSearchClientInterface::class, ElasticSearchAdapter::class);
    }
}
