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
 * Class LoggableInterceptor
 */
class LoggableInterceptor extends AbstractLogger implements MethodInterceptor
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
        $start = microtime(true);
        $result = $invocation->proceed();
        $time = microtime(true) - $start;
        /** @var \Ytake\LaravelAspect\Annotation\Loggable $annotation */
        $annotation = $this->reader->getMethodAnnotation($invocation->getMethod(), $this->annotation);
        $logFormat = $this->logFormatter($annotation, $invocation);
        $logger = app('Psr\Log\LoggerInterface');
        if ($logger instanceof Writer) {
            $logger = $logger->getMonolog();
        }
        if (!$annotation->skipResult) {
            $logFormat['context']['result'] = $result;
        }
        $logFormat['context']['time'] = $time;
        /** Monolog\Logger */
        $logger->log($logFormat['level'], $logFormat['message'], $logFormat['context']);

        return $result;
    }
}
