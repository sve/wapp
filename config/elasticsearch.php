<?php

return [
    'hosts' => [
        env('ELASTICSEARCH_HOST', 'localhost'),
    ],
    'indices' => [
        'default' => 'default',
    ]
];
