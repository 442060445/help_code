<?php
return [
    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

//        'cluster' => env('REDIS_CLUSTER', false),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
        ],

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],
];