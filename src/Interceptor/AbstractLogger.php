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

use Psr\Log\LoggerInterface;
use Ray\Aop\MethodInvocation;
use Bssd\LaravelAspect\Annotation\LoggableAnnotate;

use function sprintf;

/**
 * Class AbstractLogger
 */
abstract class AbstractLogger
{
    /** @var string */
    protected $format = "%s:%s.%s";

    /** @var LoggerInterface */
    protected static $logger;

    /**
     * @param LoggableAnnotate $annotation
     * @param MethodInvocation $invocation
     * @return array
     * @throws \ReflectionException
     */
    protected function logFormatter(
        LoggableAnnotate $annotation,
        MethodInvocation $invocation
    ): array {
        $context = [];
        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            $context['args'][$parameter->name] = isset($arguments[$parameter->getPosition()]) ?
                $arguments[$parameter->getPosition()] :
                    ($parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
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

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        static::$logger = $logger;
    }
}
