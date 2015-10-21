<?php

return [

    'aspect' => [
        /**
         * choose aop library
         * "ray"(Ray.Aop), "none"(for testing)
         */
        'default' => env('ASPECT_DRIVER', 'ray'),

        /**
         *
         */
        'drivers'     => [
            'ray'  => [
                // string Path to the cache directory where compiled classes will be stored
                'cache_dir' => storage_path('framework/aop'),
            ],
            'none' => [
                // for testing driver
                // no use aspect
            ]
        ],
    ],

    'annotation' => [
        /**
         * choose annotation reader
         * 'array'(default), 'file'(file cache), 'cache'(apc)
         */
        'default' => env('ASPECT_DRIVER', 'array'),

        /**
         * only file, cache driver
         */
        'debug' => true,

        'drivers' => [
            'file' => [
                'cache_dir' => storage_path('framework/annotation'),
                //
                'debug' => true,
            ],
            'cache' => [
                //
                'debug' => true,
            ]
        ],
    ],
];
