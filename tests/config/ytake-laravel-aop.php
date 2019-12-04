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
                'force_compile' => false,

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
    ],

    'annotation' => [
        'ignores' => [
            // global Ignored Annotations
            'Get',
            'Resource'
        ],
        'custom' => [
            // added your annotation class
        ],
    ],
];
