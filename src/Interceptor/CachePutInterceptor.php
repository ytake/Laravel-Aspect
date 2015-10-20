<?php

namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;

/**
 * Class AroundCachePutAspect
 */
class CachePutInterceptor extends AbstractCache
{
    /**
     * @param MethodInvocation $invocation
     *
     * @return mixed
     */
    public function invoke(MethodInvocation $invocation)
    {
        $annotation = $this->reader
            ->getMethodAnnotation($invocation->getMethod(), $this->annotation);

        $keys = $this->generateCacheName($annotation->cacheName, $invocation);
        if (!is_array($annotation->key)) {
            $annotation->key = [$annotation->key];
        }
        $keys = $this->detectCacheKeys($invocation, $annotation, $keys);
        // detect use cache driver
        $cache = $this->detectCacheRepository($annotation);

        if ($result = $invocation->proceed()) {
            $cache->put(implode($this->join, $keys), $result, $annotation->lifetime);
        }

        return $result;
    }
}
