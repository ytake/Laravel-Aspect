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
 * Copyright (c) 2015-2017 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Ytake\LaravelAspect\Annotation\Async;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class AsyncInterceptor
 * @deprecated
 * @codeCoverageIgnore
 */
class AsyncInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /**
     * @param MethodInvocation $invocation
     *
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        /** @var Async $annotation */
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation);
        $stack = [];
        for ($i = 1; $i <= $annotation->process; $i++) {
            $pid = pcntl_fork();
            if ($pid === -1) {
                throw new \RuntimeException('pcntl_fork() returned -1');
            } elseif ($pid) {
                $stack[$pid] = true;
                if (count($stack) >= $annotation->process) {
                    unset($stack[pcntl_waitpid(-1, $status, WUNTRACED)]);
                }

                return null;
            } else {
                $invocation->proceed();
                exit;
            }
        }
        while (count($stack) > 0) {
            unset($stack[pcntl_waitpid(-1, $status, WUNTRACED)]);
        }
    }
}
