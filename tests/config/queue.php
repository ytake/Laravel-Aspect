<?php
return [
    'default' => 'sync',

    'connections' => [
        'sync'     => [
            'driver' => 'sync',
        ],
        'database' => [
            'driver'      => 'database',
            'table'       => 'jobs',
            'queue'       => 'default',
            'retry_after' => 90,
        ],
        'redis'    => [
            'driver'      => 'redis',
            'connection'  => 'default',
            'queue'       => 'default',
            'retry_after' => 90,
        ],
    ],

    'failed' => [
        'database' => 'mysql',
        'table'    => 'failed_jobs',
    ],
];
