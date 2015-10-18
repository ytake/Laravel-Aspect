<?php

return [
    'default' => 'ray',
    /**
     *
     */
    'aop' => [
        'ray' => [
            'cache_dir' => __DIR__ . '/../storage',
        ],
        'none' => [
            // for testing driver
            // no use aspect
        ]
    ],
];
