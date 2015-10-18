<?php

namespace Ytake\LaravelAspect\Execution;

use Ray\Aop\Bind;
use Ray\Aop\Compiler;
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
     */
    public function bootstrap(Application $app, Compiler $compiler)
    {
        $cache = new AroundCacheableAspect($app['cache']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);
        $pointcut = new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );
        $bind = (new Bind)->bind($class, [$pointcut]);
        /*
        $app->bind($class, function (Application $app) use ($bind, $compiler, $class) {
            $class = $compiler->compile($class, $bind);
            $reflection = $app->make($class);
            $reflection->bindings = $bind->getBindings();
            return $reflection;
        });
        */
    }
}
