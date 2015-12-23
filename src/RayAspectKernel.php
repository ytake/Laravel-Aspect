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
 * Copyright (c) 2015 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelAspect;

use Ray\Aop\Compiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Container\Container;
use Ytake\LaravelAspect\Exception\ClassNotFoundException;

/**
 * Class RayAspectKernel
 */
class RayAspectKernel implements AspectDriverInterface
{
    /** @var Container */
    protected $app;

    /** @var array */
    protected $configure;

    /** @var Compiler */
    protected $compiler;

    /** @var Filesystem */
    protected $filesystem;

    /** @var bool */
    protected $cacheable = false;

    /** @var \Ytake\LaravelAspect\Modules\AspectModule */
    protected $aspectResolver;

    /**
     * RayAspectKernel constructor.
     * @param Container $app
     * @param Filesystem $filesystem
     * @param array $configure
     */
    public function __construct(Container $app, Filesystem $filesystem, array $configure)
    {
        $this->app = $app;
        $this->filesystem = $filesystem;
        $this->configure = $configure;
        $this->makeCompileDir();
        $this->makeCacheableDir();
        $this->compiler = $this->getCompiler();
    }

    /**
     * @param null $module
     *
     * @throws ClassNotFoundException
     */
    public function register($module = null)
    {
        if (!class_exists($module)) {
            throw new ClassNotFoundException($module);
        }
        $this->aspectResolver = (new $module($this->app));
        $this->aspectResolver->attach();
    }

    /**
     * boot aspect kernel
     */
    public function dispatch()
    {
        foreach ($this->aspectResolver->getResolver() as $class => $pointcuts) {
            $bind = (new AspectBind($this->filesystem, $this->configure['cache_dir'], $this->cacheable))
                ->bind($class, $pointcuts);
            $compiledClass = $this->compiler->compile($class, $bind);
            $this->app->bind($class, function ($app) use ($bind, $compiledClass) {
                $instance = $app->make($compiledClass);
                $instance->bindings = $bind->getBindings();
                return $instance;
            });
        }
    }

    /**
     * @return Compiler
     */
    protected function getCompiler()
    {
        return new Compiler($this->configure['compile_dir']);
    }

    /**
     * make source compile file directory
     *
     * @return void
     */
    protected function makeCompileDir()
    {
        // @codeCoverageIgnoreStart
        if (!$this->filesystem->exists($this->configure['compile_dir'])) {
            $this->filesystem->makeDirectory($this->configure['compile_dir'], 0777, true);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * make aspect cache directory
     *
     * @return void
     */
    protected function makeCacheableDir()
    {
        if ($this->configure['cache']) {
            // @codeCoverageIgnoreStart
            if (!$this->filesystem->exists($this->configure['cache_dir'])) {
                $this->filesystem->makeDirectory($this->configure['cache_dir'], 0777, true);
            }
            // @@codeCoverageIgnoreEnd
            $this->cacheable = true;
        }
    }
}
