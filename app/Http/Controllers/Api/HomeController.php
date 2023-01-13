<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use App\Repositories\UserLocationRepository;
use App\Services\WeatherService;

class HomeController extends Controller
{
    public function __construct(
        protected WeatherService $weatherService,
        protected UserLocationRepository $userLocationRepository
    ) {

    }

    /**
     * Display a listing of the resource.
     *
     * @return HomeResource
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(): HomeResource
    {
        $user = auth()->user();
        $location = $this->userLocationRepository
            ->forUser($user)
            ->latest()
            ->first();

        $user->weather = $this->weatherService->byUserLocation($location);

        return HomeResource::make($user);
    }
}
