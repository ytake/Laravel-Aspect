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

namespace Ytake\LaravelAspect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Console\Input\InputArgument;

use function str_replace;
use function trim;

/**
 * Class ModulePublishCommand
 *
 * @codeCoverageIgnore
 */
class ModulePublishCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:aspect-module-publish';

    /** @var string */
    protected $description = 'Publish any aspect modules from ytake/laravel-aspect';

    /** @var Filesystem */
    protected $filesystem;

    /** @var string */
    protected $classPath = 'Modules';

    /** @var array  package modules */
    protected $modules = [
        'CacheableModule' => 'Ytake\LaravelAspect\Modules\CacheableModule',
        'CacheEvictModule' => 'Ytake\LaravelAspect\Modules\CacheEvictModule',
        'CachePutModule' => 'Ytake\LaravelAspect\Modules\CachePutModule',
        'TransactionalModule' => 'Ytake\LaravelAspect\Modules\TransactionalModule',
        'LoggableModule' => 'Ytake\LaravelAspect\Modules\LoggableModule',
        'LogExceptionsModule' => 'Ytake\LaravelAspect\Modules\LogExceptionsModule',
        'PostConstructModule' => 'Ytake\LaravelAspect\Modules\PostConstructModule',
        'RetryOnFailureModule' => 'Ytake\LaravelAspect\Modules\RetryOnFailureModule',
        'MessageDrivenModule' => 'Ytake\LaravelAspect\Modules\MessageDrivenModule',
        'QueryLogModule' => 'Ytake\LaravelAspect\Modules\QueryLogModule',
    ];

    /**
     * @param  Filesystem  $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \ReflectionException
     */
    public function handle(): void
    {
        foreach ($this->modules as $className => $module) {
            $path = $this->getPath($this->parseClassName($className, $this->argument('module_dir')));

            if ($this->filesystem->exists($path)) {
                continue;
            }
            $stub = $this->filesystem->get($this->stub());
            $extendClassName = $this->getExtendsClassName($module);
            $source = str_replace(
                [
                    'DummyNamespace',
                    'DummyClass',
                    'DummyAspectModuleClass',
                    'DummyModuleClass',
                ],
                [
                    $this->laravel->getNamespace().$this->argument('module_dir'),
                    $className,
                    $module.' as '.$extendClassName,
                    $extendClassName,
                ],
                $stub
            );
            $this->makeDirectory($path);
            $this->filesystem->put($path, $source);
            $this->info($path.' created successfully.');
        }
    }

    /**
     * @param  string  $name
     *
     * @return string
     */
    protected function getPath(string $name): string
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string       $name
     * @param  string|null  $moduleDirectory
     *
     * @return string
     */
    protected function parseClassName(string $name, string $moduleDirectory = null): string
    {
        $rootNamespace = $this->laravel->getNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->parseClassName(
            trim($rootNamespace, '\\').'\\'.$moduleDirectory.'\\'.$name
        );
    }

    /**
     * @return string
     */
    protected function stub(): string
    {
        /** module stub file path */
        return __DIR__.'/stub/ModuleStub.stub';
    }

    /**
     * @param  string  $module
     *
     * @return string
     * @throws \ReflectionException
     */
    protected function getExtendsClassName(string $module): string
    {
        $shortName = (new ReflectionClass($module))->getShortName();
        $extendClassName = "Package{$shortName}";

        return $extendClassName;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     */
    protected function makeDirectory(string $path): void
    {
        if (!$this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0777, true, true);
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
     * added custom aspect module, override package modules
     *
     * @param  string  $module
     *
     * @return ModulePublishCommand
     */
    protected function addModule(string $module): self
    {
        $this->modules[$module];

        return $this;
    }
}
