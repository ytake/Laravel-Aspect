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
use Illuminate\Contracts\Bus\Dispatcher;
use Bssd\LaravelAspect\Queue\LazyMessage;
use Bssd\LaravelAspect\Queue\EagerMessage;
use Bssd\LaravelAspect\Annotation\LazyQueue;
use Bssd\LaravelAspect\Annotation\MessageDriven;
use Bssd\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class MessageDrivenInterceptor
 */
class MessageDrivenInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var Dispatcher */
    protected static $dispatcher;

    /**
     * @param MethodInvocation $invocation
     *
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        /** @var MessageDriven $annotation */
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation) ?? new $this->annotation([]);
        $command = new EagerMessage($invocation);
        if ($annotation->value instanceof LazyQueue) {
            $command = new LazyMessage($invocation);
            $command->onQueue($annotation->onQueue)
                ->delay($annotation->value->delay())
                ->onConnection($annotation->mappedName);
        }

        return static::$dispatcher->dispatch($command);
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setBusDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }
}
