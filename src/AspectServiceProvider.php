<?php

namespace Ytake\LaravelAspect;

use Illuminate\Support\ServiceProvider;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class AspectServiceProvider
 */
class AspectServiceProvider extends ServiceProvider
{
    /**
     * boot serivce
     */
    public function boot()
    {
        // register annotation
        $this->app->make('aspect.annotation.register')->registerAspectAnnotations();
        // boot aspect kernel
        $this->app->make('aspect.manager')->register();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        /**
         * for package configure
         */
        $configPath = __DIR__ . '/config/ytake-laravel-aop.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-aop');
        $this->publishes([$configPath => config_path('ytake-laravel-aop.php')], 'aspect');

        $this->app->singleton('aspect.annotation.register', function () {
            return new Annotation();
        });

        $this->app->singleton('aspect.annotation.reader', function () {
            return new AnnotationReader;
        });

        $this->app->singleton('aspect.manager', function ($app) {
            return new AspectManager($app);
        });
    }
}
