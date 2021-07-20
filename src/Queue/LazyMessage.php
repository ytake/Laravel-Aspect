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

namespace Bssd\LaravelAspect\Queue;

use ReflectionClass;
use Ray\Aop\MethodInvocation;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use function get_class;
use function array_values;

/**
 * Class LazyMessage
 */
class LazyMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var MethodInvocation */
    protected $methodInvocation;

    /**
     * LazyMessage constructor.
     *
     * @param MethodInvocation $invocation
     */
    public function __construct(MethodInvocation $invocation)
    {
        $this->methodInvocation = $invocation;
    }

    /**
     * @param Container $container
     *
     * @throws \ReflectionException
     */
    public function handle(Container $container)
    {
        $class = new ReflectionClass(get_class($this->methodInvocation->getThis()));
        if ($class->getFileName()) {
            $method = $this->methodInvocation->getMethod()->getName();
            $parameters = $this->methodInvocation->getMethod()->getParameters();
            $array = array_values($this->methodInvocation->getArguments()->getArrayCopy());
            $arguments = [];
            foreach ($parameters as $k => $parameter) {
                $arguments[$parameter->getName()] = $array[$k];
            }
            $container->call(
                [
                    $this->methodInvocation->getThis(),
                    $method,
                ],
                $arguments
            );
        }
    }
}
