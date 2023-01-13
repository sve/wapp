<?php

namespace App\Clients;

use App\Interfaces\OpenWeatherMapClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class OpenWeatherMapClient implements OpenWeatherMapClientInterface
{
    /**
     * @var string
     */
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5/';

    /**
     * @var int
     */
    protected int $timeout = 3;

    /**
     * @var PendingRequest
     */
    protected PendingRequest $client;

    public function __construct()
    {
        return $this->client = Http::acceptJson()
            ->withUserAgent(sprintf('%s Client', env('APP_NAME')))
            ->baseUrl($this->baseUrl)
            ->timeout($this->timeout);
    }

    /**
     * @param string $query
     * @return Response
     */
    public function requestByLocation(string $query): Response
    {
        return $this->requestWeather([
            'q' => $query
        ]);
    }

    /**
     * @param string $query
     * @return array
     */
    public function getByLocation(string $query): array
    {
        return $this->requestByLocation($query)
            ->json();
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @return Response
     */
    public function requestByCoordinates(float $longitude, float $latitude): Response
    {
        return $this->requestWeather([
            'lon' => $longitude,
            'lat' => $latitude,
        ]);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     * @return array
     */
    public function getByCoordinates(float $longitude, float $latitude): array
    {
        return $this->requestByCoordinates($longitude, $latitude)
            ->json();
    }

    /**
     * @param array $arguments
     * @return \GuzzleHttp\Promise\PromiseInterface|Response
     */
    public function requestWeather(array $arguments = []): mixed
    {
        return $this->client
            ->asJson()
            ->get('weather', array_merge([
                'appid' => config('services.openweathermap.key'),
            ], $arguments));
    }
}
