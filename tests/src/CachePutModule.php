<?php

namespace __Test;

class CachePutModule extends \Bssd\LaravelAspect\Modules\CachePutModule
{
    /**
     * @var array
     */
    protected $classes = [
        \__Test\AspectCachePut::class,
    ];
}
