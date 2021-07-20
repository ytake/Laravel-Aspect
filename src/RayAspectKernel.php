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

namespace Bssd\LaravelAspect;

use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Ray\Aop\Compiler;
use Ray\Aop\Weaver;
use Bssd\LaravelAspect\Exception\ClassNotFoundException;
use Bssd\LaravelAspect\Modules\AspectModule;

use function class_exists;
use function count;
use function strval;
use function serialize;
use function unserialize;

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

    /** @var bool */
    protected $forceCompile = false;

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
     *
     * @throws ClassNotFoundException
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
     * @param string $module
     *
     * @throws ClassNotFoundException
     */
    public function register(string $module = null): void
    {
        if (!class_exists($module)) {
            throw new ClassNotFoundException($module);
        }
        $this->modules[] = new $module;
    }

    /**
     * weaving
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \ReflectionException
     */
    public function weave(): void
    {
        if (!count($this->modules)) {
            return;
        }
        $compiler = $this->getCompiler();
        $container = $this->containerAdaptor($this->app);
        foreach ($this->aspectConfiguration() as $class => $pointcuts) {
            $bind = (new AspectBind($this->filesystem, strval($this->configure['cache_dir']), $this->cacheable))
                ->bind($class, $pointcuts);
            $weaved = $this->forceCompile
                ? $compiler->compile($class, $bind)
                : (new Weaver($bind, (string)$this->configure['compile_dir']))->weave($class);
            $container->intercept($class, $bind, $weaved);
        }
    }

    /**
     * @param Container $container
     *
     * @return ContainerInterceptor
     */
    protected function containerAdaptor(Container $container): ContainerInterceptor
    {
        return new ContainerInterceptor($container, new AnnotateClass());
    }

    /**
     * @return Compiler
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    protected function getCompiler(): Compiler
    {
        return new Compiler((string)$this->configure['compile_dir']);
    }

    /**
     * make source compile file directory
     */
    protected function makeCompileDir()
    {
        $this->makeDirectories(strval($this->configure['compile_dir']), 0775);
        $this->forceCompile = (bool)($this->configure['force_compile'] ?? false);
    }

    /**
     * make aspect cache directory
     *
     * @codeCoverageIgnore
     */
    protected function makeCacheableDir()
    {
        if ($this->configure['cache']) {
            $this->makeDirectories(strval($this->configure['cache_dir']), 0775);
            $this->cacheable = true;
        }
    }

    /**
     * @param string $dir
     * @param int    $mode
     */
    private function makeDirectories(string $dir, int $mode = 0777)
    {
        // @codeCoverageIgnoreStart
        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->makeDirectory($dir, $mode, true);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * register Aspect Module
     *
     * @throws ClassNotFoundException
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
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function aspectConfiguration(): array
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
