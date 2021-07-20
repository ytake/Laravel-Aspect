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

namespace Ytake\LaravelAspect;

use Illuminate\Contracts\Container\Container;
use Ray\Aop\BindInterface;
use Ray\Aop\WeavedInterface;

/**
 * Class ContainerInterceptor
 */
final class ContainerInterceptor
{
    /** @var Container|\Illuminate\Container\Container */
    private $container;

    /** @var AnnotateClass */
    private $annotateClass;

    /**
     * @param  Container      $container
     * @param  AnnotateClass  $annotateClass
     */
    public function __construct(Container $container, AnnotateClass $annotateClass)
    {
        $this->container = $container;
        $this->annotateClass = $annotateClass;
    }

    /**
     * @param  string         $abstract
     * @param  BindInterface  $bind
     * @param  string         $className
     *
     * @return bool
     */
    public function intercept(string $abstract, BindInterface $bind, string $className): bool
    {
        if ($abstract === $className) {
            return false;
        }

        if (isset($this->container->contextual[$abstract])) {
            $this->resolveContextualBindings($abstract, $className);
        }
        $this->container->bind($abstract, function (Container $app, array $params = []) use ($bind, $className) {
            /** @var WeavedInterface $instance */
            $instance = $app->make($className, $params);
            $instance->bindings = $bind->getBindings();
            $method = $this->annotateClass->getPostConstructMethod($instance);
            if (!empty($method)) {
                $instance->bindings = $bind->getBindings();
                $instance->$method();
            }

            return $instance;
        });

        return true;
    }

    /**
     * @param  string  $class
     * @param  string  $compiledClass
     */
    private function resolveContextualBindings(string $class, string $compiledClass): void
    {
        foreach ($this->container->contextual[$class] as $abstract => $concrete) {
            $this->container->when($compiledClass)
                ->needs($abstract)
                ->give($concrete);
        }
    }
}
