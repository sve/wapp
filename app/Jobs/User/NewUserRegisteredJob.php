<?php

namespace App\Jobs\User;

use App\Models\User;
use App\Services\ElasticSearchService;
use App\Services\WeatherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewUserRegisteredJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return void
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(): void
    {
        $weatherService = resolve(WeatherService::class);
        $elasticSearchService = resolve(ElasticSearchService::class);

        $elasticSearchService->indexUser($this->user);
        $data = $elasticSearchService->getUser($this->user);

        if (isset($data->geo->location->lat) && isset($data->geo->location->lon)) {
            $weatherService->byCoordinates(
                $data->geo->location->lon,
                $data->geo->location->lat
            );

            $this->user->locations()->create([
                'name' => implode(',', [
                    $data->geo->location->lon,
                    $data->geo->location->lat,
                ]),
            ]);
        } elseif (isset($data->geo->city_name)) {
            $weatherService->byLocation($data->geo->city_name);

            $this->user->locations()->create([
                'name' => $data->geo->city_name,
            ]);
        } else {
            logger()->info('Unable to detect user\'s location.', [
                'user_id' => $this->user->id,
                'ip' => $this->user->ip,
            ]);
        }
    }
}
