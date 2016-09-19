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
namespace Ytake\LaravelAspect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ClearAnnotationCacheCommand
 */
class ClearAnnotationCacheCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:annotation-clear-cache';

    /** @var string */
    protected $description = 'Flush the application annotation cache';

    /** @var array */
    protected $config;

    /** @var Filesystem */
    protected $filesystem;

    /**
     * @param ConfigRepository $config
     * @param Filesystem       $filesystem
     */
    public function __construct(ConfigRepository $config, Filesystem $filesystem)
    {
        parent::__construct();
        $this->config = $config->get('ytake-laravel-aop.annotation');
        $this->filesystem = $filesystem;
    }

    /**
     * @return void
     */
    public function fire()
    {
        /** @var string $driver */
        $driver = $this->argument('driver');
        if ($driver === 'file') {
            $driverConfig = $this->config['drivers']['file'];
            $directories = $this->filesystem->glob($driverConfig['cache_dir'] . '/*');
            foreach ($directories as $directory) {
                $this->filesystem->deleteDirectory($directory);
            }
        }
        if ($driver === 'apcu') {
            // clear cache
            apcu_clear_cache();
        }
        $this->info('annotation cache clear!');
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'driver',
                InputArgument::OPTIONAL,
                'The name of the driver you would like to clear.',
                $this->config['default'],
            ],
        ];
    }
}
