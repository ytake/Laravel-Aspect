<?php

namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class AbstractCache
 */
abstract class AbstractCache implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var string */
    protected $join = ":";

    /**
     * @param                  $name
     * @param MethodInvocation $invocation
     *
     * @return array
     */
    protected function generateCacheName($name, MethodInvocation $invocation)
    {
        if (is_array($name)) {
            throw new \InvalidArgumentException('Invalid argument');
        }
        if (is_null($name)) {
            $name = $invocation->getMethod()->name;
        }

        return [$name];
    }

    /**
     * @param MethodInvocation $invocation
     * @param                  $annotation
     * @param                  $keys
     *
     * @return array
     */
    protected function detectCacheKeys(MethodInvocation $invocation, $annotation, $keys)
    {
        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            // exclude object
            if (in_array('#' . $parameter->name, $annotation->key)) {
                if (!is_object($arguments[$parameter->getPosition()])) {
                    $keys[] = $arguments[$parameter->getPosition()];
                }
            }
        }

        return $keys;
    }

    /**
     * @param $annotation
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function detectCacheRepository($annotation)
    {
        $cache = app('cache')->store($annotation->driver);
        if (count($annotation->tags)) {
            $cache = $cache->tags($annotation->tags);

            return $cache;
        }

        return $cache;
    }
}
