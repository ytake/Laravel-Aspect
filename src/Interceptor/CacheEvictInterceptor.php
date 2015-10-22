<?php

namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;

/**
 * Class CacheEvictInterceptor
 */
class CacheEvictInterceptor extends AbstractCache
{
    /**
     * @param MethodInvocation $invocation
     *
     * @return mixed
     */
    public function invoke(MethodInvocation $invocation)
    {
        $result = $invocation->proceed();

        $annotation = $this->reader
            ->getMethodAnnotation($invocation->getMethod(), $this->annotation);

        $keys = $this->generateCacheName($annotation->cacheName, $invocation);
        if (!is_array($annotation->key)) {
            $annotation->key = [$annotation->key];
        }
        $keys = $this->detectCacheKeys($invocation, $annotation, $keys);

        // detect use cache driver
        $cache = $this->detectCacheRepository($annotation);

        if ($annotation->allEntries) {
            $cache->flush();
        }

        $cache->forget(implode($this->join, $keys));
        return $result;
    }
}