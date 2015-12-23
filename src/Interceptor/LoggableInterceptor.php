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
 * Copyright (c) 2015 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Ytake\LaravelAspect\Annotation\Loggable;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class LoggableInterceptor
 */
class LoggableInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var string */
    protected $format = "%s:%s.%s";

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
        /** @var \Monolog\Logger $logger */
        $logger = app('log')->getMonoLog();
        if (!$annotation->skipResult) {
            $logFormat['context']['result'] = $result;
        }
        $logFormat['context']['time'] = $time;
        /** Monolog\Logger */
        $logger->log($logFormat['level'], $logFormat['message'], $logFormat['context']);

        return $result;
    }

    /**
     * @param Loggable         $annotation
     * @param MethodInvocation $invocation
     *
     * @return string[]
     */
    protected function logFormatter(Loggable $annotation, MethodInvocation $invocation)
    {
        $context = [];
        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            $context['args'][$parameter->name] = $arguments[$parameter->getPosition()];
        }

        return [
            'level'   => $annotation->value,
            'message' => sprintf(
                $this->format,
                $annotation->name,
                $invocation->getMethod()->class,
                $invocation->getMethod()->name
            ),
            'context' => $context,
        ];
    }
}
