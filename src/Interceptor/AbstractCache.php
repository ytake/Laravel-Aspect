<?php
declare(strict_types=1);

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
 * Copyright (c) 2015-2020 Yuuki Takezawa
 *
 */

namespace Bssd\LaravelAspect\Interceptor;

use Illuminate\Contracts\Cache\Repository;
use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Factory;
use Doctrine\Common\Annotations\Annotation;
use Bssd\LaravelAspect\Annotation\AnnotationReaderTrait;
use Bssd\LaravelAspect\Annotation\Cacheable;
use Bssd\LaravelAspect\Annotation\CacheEvict;
use Bssd\LaravelAspect\Annotation\CachePut;

use function in_array;
use function is_array;
use function is_object;
use function is_null;
use function count;
use function get_class;

/**
 * Class AbstractCache
 */
abstract class AbstractCache implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var string */
    protected $join = ":";

    /** @var Factory|\Illuminate\Cache\CacheManager */
    protected static $factory;

    /**
     * @param string|array     $name
     * @param MethodInvocation $invocation
     *
     * @return array
     */
    protected function generateCacheName($name, MethodInvocation $invocation): array
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
     * @param MethodInvocation                         $invocation
     * @param Annotation|Cacheable|CacheEvict|CachePut $annotation
     * @param array                                    $keys
     *
     * @return array
     */
    protected function detectCacheKeys(
        MethodInvocation $invocation,
        Annotation $annotation,
        array $keys
    ): array {
        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            // exclude object
            if (in_array('#' . $parameter->name, $annotation->key)) {
                if (isset($arguments[$parameter->getPosition()])) {
                    if (!is_object($arguments[$parameter->getPosition()])) {
                        $keys[] = $arguments[$parameter->getPosition()];
                    }
                }
                if (!isset($arguments[$parameter->getPosition()])) {
                    $keys[] = $parameter->getDefaultValue();
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
    protected function detectCacheRepository($annotation): Repository
    {
        /** @var Factory|CacheManager $cacheFactory */
        $cacheFactory = self::$factory;
        $driver = (is_null($annotation->driver)) ? $cacheFactory->getDefaultDriver() : $annotation->driver;
        /** @var \Illuminate\Contracts\Cache\Repository|\Illuminate\Cache\TaggableStore $cache */
        $cache = $cacheFactory->store($driver);
        if (count($annotation->tags)) {
            $cache = $cache->tags($annotation->tags);

            return $cache;
        }

        return $cache;
    }

    /**
     * set cache instance
     *
     * @param Factory $factory
     */
    public function setCache(Factory $factory): void
    {
        static::$factory = $factory;
    }

    /**
     * @param string $glue
     * @param array  $array
     *
     * @return string
     */
    protected function recursiveImplode(string $glue, array $array): string
    {
        $return = '';
        $index = 0;
        $count = count($array);
        foreach ($array as $row) {
            if (is_array($row)) {
                $return .= $this->recursiveImplode($glue, $row);
            } else {
                $return .= (is_object($row)) ? get_class($row) : $row;
            }
            if ($index < $count - 1) {
                $return .= $glue;
            }
            ++$index;
        }

        return $return;
    }
}
