<?php

namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Ytake\LaravelAspect\Aspect\AroundCacheableAspect;
use Illuminate\Contracts\Foundation\Application;

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
     * @param Application $app
     *
     * @return Pointcut
     */
    public function configure(Application $app)
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
