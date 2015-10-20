<?php

namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Compiler;
use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Contracts\Foundation\Application;
use Ytake\LaravelAspect\Aspect\AroundCachePutAspect;

/**
 * Class CachePutPointCut
 */
class CachePutPointCut
{
    /** @var   */
    protected $annotation = \Ytake\LaravelAspect\Annotation\CachePut::class;

    /**
     * @param Application $app
     *
     * @return Pointcut
     */
    public function configure(Application $app)
    {
        $cache = new AroundCachePutAspect($app['cache']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);
        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );
    }
}
