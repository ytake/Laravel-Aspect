<?php

namespace Ytake\LaravelAspect;

use Illuminate\Support\ServiceProvider;

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

        $this->app->singleton('aspect.annotation.reader', function ($app) {
            return (new AnnotationManager($app))->getReader();
        });

        $this->app->singleton('aspect.manager', function ($app) {
            return new AspectManager($app);
        });
    }
}
