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

use Illuminate\Log\Writer;
use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Ytake\LaravelAspect\Annotation\QueryLog;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

/**
 * Class QueryLogInterceptor
 */
class QueryLogInterceptor extends AbstractLogger implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var EventDispatcher */
    protected static $dispatcher;

    /** @var array[] */
    protected $queryLogs = [];

    /**
     * @param MethodInvocation $invocation
     *
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        /** @var \Ytake\LaravelAspect\Annotation\QueryLog $annotation */
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation);
        $this->subscribeQueryLog();
        $result = $invocation->proceed();
        $logFormat = $this->queryLogFormatter($annotation, $invocation);
        $logger = static::$logger;
        if ($logger instanceof Writer) {
            $logger = $logger->getMonolog();
        }
        /** Monolog\Logger */
        $logger->log($logFormat['level'], $logFormat['message'], $logFormat['context']);
        $this->queryLogs = [];

        return $result;
    }

    /**
     *
     */
    protected function subscribeQueryLog()
    {
        static::$dispatcher->listen(QueryExecuted::class, function (QueryExecuted $executed) {
            $this->queryLogs[] = [
                'query'          => $executed->sql,
                'bindings'       => $executed->bindings,
                'time'           => $executed->time,
                'connectionName' => $executed->connectionName,
            ];
        });
    }

    /**
     * @param QueryLog         $annotation
     * @param MethodInvocation $invocation
     *
     * @return array
     */
    protected function queryLogFormatter(QueryLog $annotation, MethodInvocation $invocation)
    {
        return [
            'level'   => $annotation->value,
            'message' => sprintf(
                $this->format,
                $annotation->name,
                $invocation->getMethod()->class,
                $invocation->getMethod()->name
            ),
            'context' => [
                'queries' => $this->queryLogs,
            ],
        ];
    }

    /**
     * @param EventDispatcher $dispatcher
     */
    public function setDispatcher(EventDispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }
}
