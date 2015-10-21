<?php

namespace Ytake\LaravelAspect;

use Illuminate\Support\ServiceProvider;
use Ytake\LaravelAspect\Console\ClearAnnotationCacheCommand;
use Ytake\LaravelAspect\Console\ClearCacheCommand;

/**
 * Class AspectServiceProvider
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = true;

    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // register bindings
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.ytake.aspect.clear-cache', function ($app) {
            return new ClearCacheCommand($app['config'], $app['files']);
        });
        $this->app->singleton('command.ytake.annotation.clear-cache', function ($app) {
            return new ClearAnnotationCacheCommand($app['config'], $app['files']);
        });
        $this->commands([
            'command.ytake.aspect.clear-cache',
            'command.ytake.annotation.clear-cache'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'command.ytake.aspect.clear-cache',
            'command.ytake.annotation.clear-cache'
        ];
    }
}
