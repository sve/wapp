<?php

namespace App\Console\Commands\ElasticSearch;

use App\Interfaces\ElasticSearchClientInterface;
use Illuminate\Console\Command;

class SetupIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:setup-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $elasticsearchClient = resolve(ElasticSearchClientInterface::class);

        $settings = [
            'transient' => [
                'cluster.routing.allocation.disk.watermark.low' => '95%',
                'cluster.routing.allocation.disk.watermark.high' => '97%',
                'cluster.routing.allocation.disk.watermark.flood_stage' => '98%',
                'cluster.info.update.interval' => '1m',
            ],
            'persistent' => [
                'ingest' => [
                    'geoip' => [
                        'downloader' => [
                            'enabled' => 'true',
                        ],
                    ],
                ],
            ],
        ];

        $elasticsearchClient->cluster()->putSettings([
            'body' => $settings
        ]);

        $pipeline = [
            'description' => 'Add geoip info',
            'processors' => [
                [
                    'geoip' => [
                        'field' => 'ip',
                        'target_field' => 'geo',
                        'database_file' => 'GeoLite2-City.mmdb',
                        'ignore_missing' => true,
                    ],
                ],
            ],
        ];

        $elasticsearchClient->ingest()->putPipeline([
            'id' => 'geoip',
            'body' => $pipeline
        ]);

        $index = [
            'settings' => [
                'index' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 1,
                ],
            ],
            'mappings' => [
                'properties' => [
                    'ip' => [
                        'type' => 'text',
                    ],
                ],
            ],
        ];

        $elasticsearchClient->indices()->create([
            'index' => config('elasticsearch.indices.default'),
            'body' => $index,
        ]);
    }
}
