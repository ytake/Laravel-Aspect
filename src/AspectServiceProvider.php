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
     * {@inheritdoc}
     */
    public function boot()
    {
        // register annotation
        $this->app->make('aspect.annotation.register')->registerAspectAnnotations();
        // annotation driver
        $this->app->make('aspect.manager')->register();
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        /**
         * for package configure
         */
        $configPath = __DIR__ . '/config/ytake-laravel-aop.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-aop');
        $this->publishes([$configPath => config_path('ytake-laravel-aop.php')], 'aspect');

        $this->registerAspectAnnotations();

        $this->app->singleton('aspect.annotation.reader', function () {
            return new AnnotationReader;
        });

        $this->app->singleton('aspect.manager', function ($app) {
            return new AspectManager($app);
        });
    }

    /**
     * @return void
     */
    protected function registerAspectAnnotations()
    {
        $this->app->singleton('aspect.annotation.register', function () {
            return new Annotation();
        });
    }
}
