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
 * Copyright (c) 2015 Yuuki Takezawa
 *
 *
 * CodeGenMethod Class, CodeGen Class is:
 * Copyright (c) 2012-2015, The Ray Project for PHP
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

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
        $result = $invocation->proceed();

        if ($result) {
            $cache->add($key, $result, $annotation->lifetime);
        }

        return $result;
    }
}
