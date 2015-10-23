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
 *
 * CodeGenMethod Class, CodeGen Class is:
 * Copyright (c) 2012-2015, The Ray Project for PHP
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

namespace Ytake\LaravelAspect\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ModulePublishCommand
 */
class ModulePublishCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:aspect-module-publish';

    /** @var string */
    protected $description = 'Publish any aspect modules from ytake/laravel-aspect';

    /** @var string  module stub file path */
    protected $stub = __DIR__ . '/stub/ModuleStub.stub';

    /** @var Filesystem */
    protected $filesystem;

    /** @var string */
    protected $classPath = 'Modules';

    /** @var array  package modules */
    protected $modules = [
        'CacheableModule'     => \Ytake\LaravelAspect\Modules\CacheableModule::class,
        'CacheEvictModule'    => \Ytake\LaravelAspect\Modules\CacheEvictModule::class,
        'CachePutModule'      => \Ytake\LaravelAspect\Modules\CachePutModule::class,
        'TransactionalModule' => \Ytake\LaravelAspect\Modules\TransactionalModule::class,
    ];

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * @return void
     */
    public function fire()
    {
        foreach ($this->modules as $className => $module) {
            $path = $this->getPath($this->parseClassName($className, $this->argument('module_dir')));

            if ($this->filesystem->exists($path)) {
                continue;
            }
            $stub = $this->filesystem->get($this->stub);
            $extendClassName = $this->getExtendsClassName($module);
            $source = str_replace(
                [
                    'DummyNamespace',
                    'DummyClass',
                    'DummyAspectModuleClass',
                    'DummyModuleClass'
                ],
                [
                    $this->laravel->getNamespace() . $this->argument('module_dir'),
                    $className,
                    $module . ' as ' . $extendClassName,
                    $extendClassName
                ], $stub);
            $this->makeDirectory($path);
            $this->filesystem->put($path, $source);
            $this->info($path . ' created successfully.');
        }
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module_dir', InputArgument::OPTIONAL, 'The name of the class directory', $this->classPath],
        ];
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function parseClassName($name, $moduleDirectory = null)
    {
        $rootNamespace = $this->laravel->getNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->parseClassName(
            trim($rootNamespace, '\\') . '\\' . $moduleDirectory . '\\' . $name
        );
    }

    /**
     * added custom aspect module, override package modules
     *
     * @param $module
     * @return $this
     */
    protected function addModule($module)
    {
        $this->modules[$module];

        return $this;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * @param $module
     * @return string
     */
    protected function getExtendsClassName($module)
    {
        $shortName = (new \ReflectionClass($module))->getShortName();
        $extendClassName = "Package{$shortName}";

        return $extendClassName;
    }
}
