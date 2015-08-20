<?php

namespace Ytake\LaravelAop\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * Class ClearCacheCommand
 *
 * @package Ytake\LaravelAop\Console
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class ClearCacheCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:aspect-clear-cache';

    /** @var string  */
    protected $description = 'compiles all known templates';

    /** @var ConfigRepository  */
    protected $config;

    /** @var Filesystem  */
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

        $driverConfig = $configure['aop'][$configure['default']];
        if(isset($driverConfig['cacheDir'])) {
            $this->filesystem->deleteDirectory($driverConfig['cacheDir']);
        }
        $this->info('aspect/annotation cache clear!');
    }
}
