<?php

namespace __Test;

class CacheEvictModule extends \Bssd\LaravelAspect\Modules\CacheEvictModule
{
    /**
     * @var array
     */
    protected $classes = [
        \__Test\AspectCacheEvict::class,
        \__Test\AspectMerge::class
    ];
}
