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

use Illuminate\Log\Writer;
use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class LogExceptionsInterceptor
 */
class LogExceptionsInterceptor extends AbstractLogger implements MethodInterceptor
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
        /** @var \Ytake\LaravelAspect\Annotation\LogExceptions $annotation */
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation);
        try {
            $result = $invocation->proceed();
        } catch (\Exception $exception) {
            if ($exception instanceof $annotation->expect) {
                $logFormat = $this->logFormatter($annotation, $invocation);
                $logger = self::$logger;
                if ($logger instanceof Writer) {
                    $logger = $logger->getMonolog();
                }
                /** Monolog\Logger */
                $logFormat['context']['code'] = $exception->getCode();
                $logFormat['context']['error_message'] = $exception->getMessage();
                $logger->log($logFormat['level'], $logFormat['message'], $logFormat['context']);
            }
            throw $exception;
        }

        // @codeCoverageIgnoreStart
        return $result;
        // @codeCoverageIgnoreEnd
    }
}
