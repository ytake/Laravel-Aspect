<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2015-2016 Yuuki Takezawa
 *
 */
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
