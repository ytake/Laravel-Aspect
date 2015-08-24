<?php

namespace Ytake\LaravelAspect\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Illuminate\Contracts\Cache\Factory as CacheFactory;

/**
 * Class AbstractCache
 *
 * @package Ytake\LaravelAspect\Aspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
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

    /**
     * @param MethodInvocation $invocation
     * @param                  $annotation
     * @param                  $keys
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
}
