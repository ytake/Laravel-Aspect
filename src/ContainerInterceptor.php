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
namespace Ytake\LaravelAspect;

use Ray\Aop\Bind;
use Illuminate\Contracts\Container\Container;
use Ytake\LaravelAspect\Annotation\PostConstruct;

/**
 * Class ContainerInterceptor
 */
final class ContainerInterceptor
{
    /** @var Container|\Illuminate\Container\Container */
    private $container;

    /**
     * ContainerInterceptor constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $abstract
     * @param Bind   $bind
     * @param string $className
     */
    public function intercept($abstract, Bind $bind, $className)
    {
        if ($abstract === $className) {
            return false;
        }
        
        if (isset($this->container->contextual[$abstract])) {
            $this->resolveContextualBindings($abstract, $className);
        }

        $this->container->bind($abstract, function (Container $app) use ($bind, $className) {
            $instance = $app->make($className);
            $methods = unserialize($instance->methodAnnotations);
            foreach ($methods as $method => $annotations) {
                if (array_key_exists(PostConstruct::class, $annotations)) {
                    $instance->$method();
                }
            }
            $instance->bindings = $bind->getBindings();

            return $instance;
        });
    }

    /**
     * @param string $class
     * @param string $compiledClass
     */
    private function resolveContextualBindings($class, $compiledClass)
    {
        foreach ($this->container->contextual[$class] as $abstract => $concrete) {
            $this->container->when($compiledClass)
                ->needs($abstract)
                ->give($concrete);
        }
    }
}
