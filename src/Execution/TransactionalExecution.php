<?php

namespace Ytake\LaravelAspect\Execution;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Contracts\Foundation\Application;
use Ytake\LaravelAspect\Aspect\AroundTransactionalAspect;

/**
 * Class TransactionalExecution
 */
class TransactionalExecution
{
    /** @var   */
    protected $annotation = \Ytake\LaravelAspect\Annotation\Transactional::class;

    /**
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        $cache = new AroundTransactionalAspect($app['db']);
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);
        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );

    }
}
