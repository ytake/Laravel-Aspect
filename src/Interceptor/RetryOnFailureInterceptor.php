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
use Ray\Aop\MethodInterceptor;
use Ytake\LaravelAspect\Annotation\RetryOnFailure;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class RetryOnFailureInterceptor
 */
class RetryOnFailureInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var int|null */
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
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation);
        if (static::$attempt === null) {
            static::$attempt = $annotation->attempts;
        }
        try {
            static::$attempt--;

            return $invocation->proceed();
        } catch (\Exception $e) {
            if (ltrim($annotation->ignore, '\\') === get_class($e)) {
                static::$attempt = null;
                throw $e;
            }
            $pass = array_filter($annotation->types, function ($values) use ($e) {
                return ltrim($values, '\\') === get_class($e);
            });
            if ($pass !== false) {
                if (static::$attempt > 0) {
                    sleep($annotation->delay);

                    return $invocation->proceed();
                }
            }
            static::$attempt = null;
            throw $e;
        }
    }
}
