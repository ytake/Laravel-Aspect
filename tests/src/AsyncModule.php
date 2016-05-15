<?php

namespace __Test;

class AsyncModule extends \Ytake\LaravelAspect\Modules\AsyncModule
{
    /**
     * @var array
     */
    protected $classes = [
        \__Test\Async::class,
    ];
}
