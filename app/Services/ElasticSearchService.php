<?php

namespace App\Services;

use App\Interfaces\ElasticSearchClientInterface;
use App\Models\User;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch as ElasticsearchResponse;

class ElasticSearchService
{
    public function __construct(
        protected ElasticSearchClientInterface $client
    ) {
    }

    /**
     * @param array $data
     * @return ElasticsearchResponse
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function indexToDefault(array $data): ElasticsearchResponse
    {
        $parameters = [
            'index' => config('elasticsearch.indices.default'),
            'pipeline' => 'geoip',
            'body' => $data,
        ];

        if (isset($data['id'])) {
            $parameters['id'] = $data['id'];
        }

        return $this->client->index($parameters);
    }

    /**
     * @param User $user
     * @return ElasticsearchResponse
     * @throws \Elastic\Elasticsearch\Exception\ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function indexUser(User $user): ElasticsearchResponse
    {
        $data = $user->toArray();

        return $this->indexToDefault($data);
    }

    /**
     * @param User $user
     * @return mixed
     * @throws ClientResponseException
     * @throws \Elastic\Elasticsearch\Exception\MissingParameterException
     * @throws \Elastic\Elasticsearch\Exception\ServerResponseException
     */
    public function getUser(User $user)
    {
        $response = $this->client->get([
            'index' => config('elasticsearch.indices.default'),
            'id' => $user->id,
        ]);

        if (!isset($response->asObject()->_source)) {
            throw new ClientResponseException('Document not found');
        }

        return $response->asObject()->_source;
    }
}
