<?php

namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Container\Container;
use Ytake\LaravelAspect\Interceptor\TransactionalInterceptor;

/**
 * Class TransactionalPointCut
 */
class TransactionalPointCut
{
    /** @var   */
    protected $annotation = \Ytake\LaravelAspect\Annotation\Transactional::class;

    /**
     * @param Container $app
     *
     * @return Pointcut
     */
    public function configure(Container $app)
    {
        $cache = new TransactionalInterceptor($app['db']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);
        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );

    }
}
