<?php

return [

    /**
     * choose aop library
     * "ray"(Ray.Aop), "none"(for testing)
     */
    'default' => env('ASPECT_DRIVER', 'ray'),

    //
    'debug' => env('ASPECT_DEBUG', true),

    /**
     *
     */
    'aop' => [

        'ray' => [
            // string Path to the cache directory where compiled classes will be stored
            'cache_dir' => storage_path('framework/aop'),
            // use Classes
            'Cacheable' => [

            ],
            'CacheEvict' => [

            ]
        ],
        'none' => [
            // for testing driver
            // no use aspect
        ]
    ],
];
