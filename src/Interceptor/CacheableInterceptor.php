<?php

namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;

/**
 * Class CacheableInterceptor
 */
class CacheableInterceptor extends AbstractCache
{
    /**
     * @param MethodInvocation $invocation
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
        $key = implode($this->join, $keys);
        if ($cache->has($key)) {
            return $cache->get($key);
        }
        if ($result = $invocation->proceed()) {
            $cache->add($key, $result, $annotation->lifetime);
        }
        return $result;
    }
}
