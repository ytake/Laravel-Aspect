<?php
use Monolog\Handler\StreamHandler;
return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */
    'default' => env('LOG_CHANNEL', 'stack'),
    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],
        'single' => [
            'driver' => 'single',
            'path' => __DIR__ . '/../storage/log/.testing.log',
            'level' => 'debug',
        ],
        'custom' => [
            'driver' => 'custom',
            'via' => __Test\TestableLogger::class,
            'path' => __DIR__ . '/../storage/log/.testing.exceptions.log',
            'level' => 'debug',
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => __DIR__ . '/../storage/log/.testing.log',
            'level' => 'debug',
            'days' => 7,
        ],
        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],
        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],
        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
    ],
];