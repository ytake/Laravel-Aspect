<?php

return [

    'fetch' => PDO::FETCH_CLASS,

    'default' => 'testing',

    'connections' => [
        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'options' => [
                PDO::ATTR_PERSISTENT => true,
            ]
        ],
    ],

    'migrations' => 'migrations',

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],
    ],
];
