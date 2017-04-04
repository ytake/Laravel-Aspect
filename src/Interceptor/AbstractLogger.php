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

use Psr\Log\LoggerInterface;
use Ray\Aop\MethodInvocation;
use Ytake\LaravelAspect\Annotation\LoggableAnnotate;

/**
 * Class AbstractLogger
 */
class AbstractLogger
{
    /** @var string */
    protected $format = "%s:%s.%s";

    /** @var LoggerInterface  */
    protected static $logger;

    /**
     * @param LoggableAnnotate $annotation
     * @param MethodInvocation $invocation
     *
     * @return string[]
     */
    protected function logFormatter(LoggableAnnotate $annotation, MethodInvocation $invocation)
    {
        $context = [];
        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            $context['args'][$parameter->name] =
                !isset($arguments[$parameter->getPosition()]) ? $parameter->getDefaultValue() : $arguments[$parameter->getPosition()];
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
    public function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }
}
