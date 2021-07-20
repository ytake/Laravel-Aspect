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

use Illuminate\Log\LogManager;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Bssd\LaravelAspect\Annotation\AnnotationReaderTrait;

use function is_null;

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
        /** @var \Bssd\LaravelAspect\Annotation\LogExceptions $annotation */
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation) ?? new $this->annotation([]);
        try {
            $result = $invocation->proceed();
        } catch (\Exception $exception) {
            if ($exception instanceof $annotation->expect) {
                $logFormat = $this->logFormatter($annotation, $invocation);
                $logger = static::$logger;
                /** Monolog\Logger */
                $logFormat['context']['code'] = $exception->getCode();
                $logFormat['context']['error_message'] = $exception->getMessage();
                if ($logger instanceof LogManager) {
                    if (!is_null($annotation->driver)) {
                        $logger = $logger->driver($annotation->driver);
                    }
                    $logger->addRecord($logFormat['level'], $logFormat['message'], $logFormat['context']);
                }
            }
            throw $exception;
        }

        // @codeCoverageIgnoreStart
        return $result;
        // @codeCoverageIgnoreEnd
    }
}
