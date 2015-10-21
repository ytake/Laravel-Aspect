<?php

namespace Ytake\LaravelAspect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * Class ClearCacheCommand
 */
class ClearCacheCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:aspect-clear-cache';

    /** @var string */
    protected $description = 'Flush the application aspect code cache';

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
        $configure = $this->config->get('ytake-laravel-aop');
        $driverConfig = $configure['aspect']['drivers'][$configure['aspect']['default']];
        if (isset($driverConfig['cache_dir'])) {
            $files = $this->filesystem->glob($driverConfig['cache_dir'] . '/*');
            foreach ($files as $file) {
                $this->filesystem->delete($file);
            }
        }
        $this->info('aspect code cache clear!');
    }
}
