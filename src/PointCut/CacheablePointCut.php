<?php

namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Container\Container;
use Ytake\LaravelAspect\Interceptor\CacheableInterceptor;

/**
 * Class CacheablePointCut
 *
 * @package Ytake\LaravelAspect\PointCut
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class CacheablePointCut
{
    /** @var   */
    protected $annotation = \Ytake\LaravelAspect\Annotation\Cacheable::class;

    /**
     * @param Container $app
     *
     * @return Pointcut
     */
    public function configure(Container $app)
    {
        $cache = new CacheableInterceptor($app['cache']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);
        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );
    }
}
