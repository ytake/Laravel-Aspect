<?php

return [

    'aspect' => [
        /**
         * choose aop library
         * "ray"(Ray.Aop), "none"(for testing)
         */
        'default' => 'ray',

        /**
         *
         */
        'drivers'     => [
            'ray'  => [
                // string Path to the cache directory where compiled classes will be stored
                'compile_dir' => __DIR__ . '/../storage/aop/compile',

                'cache' => false,

                'cache_dir' => __DIR__ . '/../storage/aop/cache',
            ],
            'none' => [
                // for testing driver
                // no use aspect
            ]
        ],
        'module_compile' => [
            'compile_dir' => __DIR__ . '/../storage/aop/module',
        ],
    ],

    'annotation' => [
        /**
         * choose annotation reader
         * 'array'(default), 'file'(file cache)
         */
        'default' => 'array',

        'drivers' => [
            'file' => [
                'cache_dir' => __DIR__ . '/../storage/annotation',
                //
                'debug' => true,
            ],
        ],

        'ignores' => [
            // global Ignored Annotations
            'Get',
            'Resource'
        ],
    ],
];
