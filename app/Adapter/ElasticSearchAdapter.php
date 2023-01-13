<?php

namespace App\Adapter;

use App\Interfaces\ElasticSearchClientInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Traits\ForwardsCalls;

class ElasticSearchAdapter implements ElasticSearchClientInterface
{
    use ForwardsCalls;

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @throws \Elastic\Elasticsearch\Exception\AuthenticationException
     */
    public function __construct()
    {
        $client = $this->buildClient();
        $this->setClient($client);
    }

    /**
     * @return Client
     * @throws \Elastic\Elasticsearch\Exception\AuthenticationException
     */
    public function buildClient()
    {
        return ClientBuilder::create()
            ->setHosts(config('elasticsearch.hosts'))
            ->setBasicAuthentication(env('ELASTICSEARCH_USER'), env('ELASTICSEARCH_PASS'))
            ->build();
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->forwardCallTo($this->client, $method, $arguments);
    }
}
