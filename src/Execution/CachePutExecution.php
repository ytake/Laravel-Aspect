<?php

namespace Ytake\LaravelAspect\Execution;

use Ray\Aop\Bind;
use Ray\Aop\Compiler;
use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Contracts\Foundation\Application;
use Ytake\LaravelAspect\Aspect\AroundCachePutAspect;

/**
 * Class CachePutExecution
 */
class CachePutExecution
{
    /** @var   */
    protected $annotation = \Ytake\LaravelAspect\Annotation\CachePut::class;

    /**
     * @param Application $app
     */
    public function bootstrap(Application $app, Compiler $compiler)
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
