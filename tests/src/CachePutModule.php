<?php

namespace __Test;

class CachePutModule extends \Ytake\LaravelAspect\Modules\CachePutModule
{
    /**
     * @var array
     */
    protected $classes = [
        \__Test\AspectCachePut::class,
    ];
}
