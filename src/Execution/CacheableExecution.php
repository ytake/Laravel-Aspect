<?php

namespace Ytake\LaravelAspect\Execution;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Ytake\LaravelAspect\Aspect\AroundCacheableAspect;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class CacheableExecution
 */
class CacheableExecution
{
    /** @var   */
    protected $annotation = \Ytake\LaravelAspect\Annotation\Cacheable::class;

    /**
     * @param Application $app
     * @return Pointcut
     */
    public function bootstrap(Application $app)
    {
        $cache = new AroundCacheableAspect($app['cache']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);
        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );
    }
}
