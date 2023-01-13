<?php

namespace App\Providers;

use App\Clients\OpenWeatherMapClient;
use App\Interfaces\OpenWeatherMapClientInterface;
use Illuminate\Support\ServiceProvider;

class OpenWeatherMapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(OpenWeatherMapClientInterface::class, OpenWeatherMapClient::class);
    }
}
