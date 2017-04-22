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
namespace Ytake\LaravelAspect;

use Ray\Aop\Compiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Container\Container;
use Ytake\LaravelAspect\Modules\AspectModule;
use Ytake\LaravelAspect\Exception\ClassNotFoundException;

/**
 * Class RayAspectKernel
 */
class RayAspectKernel implements AspectDriverInterface
{
    /** @var Container|\Illuminate\Container\Container */
    protected $app;

    /** @var array */
    protected $configure;

    /** @var Compiler */
    protected $compiler;

    /** @var Filesystem */
    protected $filesystem;

    /** @var bool */
    protected $cacheable = false;

    /** @var AspectModule */
    protected $aspectResolver;

    /** @var AspectModule[] */
    protected $registerModules = [];

    /** @var AspectModule[] */
    protected $modules = [];

    /** @var string */
    private $mapFile = 'aspect.map.serialize';

    /**
     * RayAspectKernel constructor.
     *
     * @param Container  $app
     * @param Filesystem $filesystem
     * @param array      $configure
     */
    public function __construct(Container $app, Filesystem $filesystem, array $configure)
    {
        $this->app = $app;
        $this->filesystem = $filesystem;
        $this->configure = $configure;
        $this->makeCompileDir();
        $this->makeCacheableDir();
        $this->registerAspectModule();
    }

    /**
     * @param null|string $module
     *
     * @throws ClassNotFoundException
     */
    public function register($module = null)
    {
        if (!class_exists($module)) {
            throw new ClassNotFoundException($module);
        }
        $this->modules[] = new $module;
    }

    /**
     * weaving
     */
    public function weave()
    {
        if (!count($this->modules)) {
            return;
        }
        $compiler = $this->getCompiler();
        $container = $this->containerAdaptor($this->app);
        foreach ($this->aspectConfiguration() as $class => $pointcuts) {
            $bind = (new AspectBind($this->filesystem, $this->configure['cache_dir'], $this->cacheable))
                ->bind($class, $pointcuts);
            $container->intercept($class, $bind, $compiler->compile($class, $bind));
        }
    }

    /**
     * @deprecated
     * boot aspect kernel
     */
    public function dispatch()
    {
        $this->weave();
    }

    /**
     * @param Container $container
     *
     * @return ContainerInterceptor
     */
    protected function containerAdaptor(Container $container)
    {
        return new ContainerInterceptor($container);
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
     */
    protected function makeCompileDir()
    {
        $this->makeDirectories($this->configure['compile_dir'], 0775);
    }

    /**
     * make aspect cache directory
     *
     * @codeCoverageIgnore
     */
    protected function makeCacheableDir()
    {
        if ($this->configure['cache']) {
            $this->makeDirectories($this->configure['cache_dir'], 0775);
            $this->cacheable = true;
        }
    }

    /**
     * @param string $dir
     * @param int    $mode
     */
    private function makeDirectories($dir, $mode = 0777)
    {
        // @codeCoverageIgnoreStart
        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->makeDirectory($dir, $mode, true);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * register Aspect Module
     */
    protected function registerAspectModule()
    {
        if (isset($this->configure['modules'])) {
            foreach ($this->configure['modules'] as $module) {
                $this->register($module);
            }
        }
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    protected function aspectConfiguration()
    {
        $map = [];
        $file = $this->configure['cache_dir'] . "/{$this->mapFile}";
        if ($this->configure['cache']) {
            if ($this->filesystem->exists($file)) {
                foreach ($this->modules as $module) {
                    $module->registerPointCut()->configure($this->app);
                }

                return unserialize($this->filesystem->get($file));
            }
        }
        foreach ($this->modules as $module) {
            $pointcut = $module->registerPointCut()->configure($this->app);
            foreach ($module->target() as $class) {
                $map[$class][] = $pointcut;
            }
        }

        if ($this->configure['cache']) {
            $this->filesystem->put($file, serialize($map));
        }

        return $map;
    }
}
