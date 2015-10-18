<?php

namespace Ytake\LaravelAspect\Aspect;

use Ray\Aop\MethodInvocation;

/**
 * Class AfterCacheEvictAspect
 */
class AfterCacheEvictAspect extends AbstractCache
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
            return $cache->flush();
        }
        $cache->forget(implode($this->join, $keys));
        return $result;
    }
}
