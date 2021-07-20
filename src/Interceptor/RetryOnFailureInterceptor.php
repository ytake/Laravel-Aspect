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

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Bssd\LaravelAspect\Annotation\RetryOnFailure;
use Bssd\LaravelAspect\Annotation\AnnotationReaderTrait;

use function ltrim;
use function get_class;
use function sleep;

/**
 * Class RetryOnFailureInterceptor
 */
final class RetryOnFailureInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var array|null */
    private static $attempt = null;

    /**
     * @param MethodInvocation $invocation
     *
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        /** @var RetryOnFailure $annotation */
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation) ?? new $this->annotation([]);
        $key = $this->keyName($invocation);

        if (isset(self::$attempt[$key]) === false) {
            self::$attempt[$key] = $annotation->attempts;
        }

        try {
            self::$attempt[$key]--;

            return $invocation->proceed();
        } catch (\Exception $e) {
            if (ltrim($annotation->ignore, '\\') === get_class($e)) {
                self::$attempt[$key] = null;
                throw $e;
            }

            $pass = array_filter($annotation->types, function ($values) use ($e) {
                return ltrim($values, '\\') === get_class($e);
            });
            if ($pass !== false) {
                if (self::$attempt[$key] > 0) {
                    sleep($annotation->delay);

                    return $invocation->proceed();
                }
            }
            self::$attempt[$key] = null;
            throw $e;
        }
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @return string
     */
    protected function keyName(MethodInvocation $invocation): string
    {
        return $invocation->getMethod()->class . "$" . $invocation->getMethod()->getName();
    }
}
