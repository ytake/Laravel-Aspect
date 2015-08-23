<?php

namespace Ytake\LaravelAop\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Illuminate\Contracts\Cache\Factory as CacheFactory;

/**
 * Class AbstractCache
 *
 * @package Ytake\LaravelAop\Aspect
 */
abstract class AbstractCache implements Aspect
{
    /** @var string */
    protected $join = ":";

    /** @var CacheFactory */
    protected $cache;

    /**
     * @param CacheFactory $cache
     */
    public function __construct(CacheFactory $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param                  $names
     * @param MethodInvocation $invocation
     * @return array|string
     */
    protected function generateCacheName($names, MethodInvocation $invocation)
    {
        if (is_null($names)) {
            $names = $invocation->getMethod()->name;
        }
        if (!is_array($names)) {
            return [$names];
        }

        return $names;
    }
}
