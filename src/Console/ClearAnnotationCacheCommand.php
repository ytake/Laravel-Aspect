<?php

namespace Ytake\LaravelAspect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * Class ClearAnnotationCacheCommand
 */
class ClearAnnotationCacheCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:annotation-clear-cache';

    /** @var string */
    protected $description = 'Flush the application annotation cache';

    /** @var ConfigRepository */
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
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    /**
     * @return void
     */
    public function fire()
    {
        $configure = $this->config->get('ytake-laravel-aop.annotation');
        $driverConfig = $configure['drivers']['file'][$configure['default']];;
        if (isset($driverConfig['cache_dir'])) {
            $files = $this->filesystem->glob($driverConfig['cache_dir'] . '/*');
            foreach ($files as $file) {
                $this->filesystem->delete($file);
            }
        }
        $this->info('annotation cache clear!');
    }
}
