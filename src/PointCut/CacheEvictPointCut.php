<?php

namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Container\Container;
use Ytake\LaravelAspect\Interceptor\CacheEvictInterceptor;

/**
 * Class CacheEvictExecution
 */
class CacheEvictPointCut
{
    /** @var string  */
    protected $annotation = \Ytake\LaravelAspect\Annotation\CacheEvict::class;

    /**
     * @param Container $app
     *
     * @return Pointcut
     */
    public function configure(Container $app)
    {
        $cache = new CacheEvictInterceptor($app['cache']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);

        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );
    }
}
