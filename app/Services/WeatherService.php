<?php

namespace App\Services;

use App\Interfaces\OpenWeatherMapClientInterface;
use App\Models\UserLocation;

class WeatherService
{
    /**
     * @param OpenWeatherMapClientInterface $openWeatherMapClient
     */
    public function __construct(
        protected OpenWeatherMapClientInterface $openWeatherMapClient
    ) {

    }

    /**
     * @param UserLocation $location
     * @return array|\Illuminate\Contracts\Cache\Repository|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function byUserLocation(UserLocation $location): mixed
    {
        return $this->byLocation($location->name);
    }

    /**
     * @param string $location
     * @return array|\Illuminate\Contracts\Cache\Repository|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function byLocation(string $location): mixed
    {
        $cacheTag = sprintf(config('cache.keys.weatherByLocation.pattern'), trim($location));
        $cacheTTL = config('cache.keys.weatherByLocation.ttl');

        if (cache()->has($cacheTag)) {
            return cache()->get($cacheTag);
        }

        $weather = $this->openWeatherMapClient->getByLocation($location);
        cache()->set($cacheTag, $weather, $cacheTTL);

        return $weather;
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @return array|\Illuminate\Contracts\Cache\Repository|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function byCoordinates(float $longitude, float $latitude): mixed
    {
        $cacheTag = sprintf(config('cache.keys.weatherByLocation.pattern'), implode(',', [
            $longitude,
            $latitude,
        ]));
        $cacheTTL = config('cache.keys.weatherByLocation.ttl');

        if (cache()->has($cacheTag)) {
            return cache()->get($cacheTag);
        }

        $weather = $this->openWeatherMapClient->getByCoordinates($longitude, $latitude);
        cache()->set($cacheTag, $weather, $cacheTTL);

        return $weather;
    }
}
