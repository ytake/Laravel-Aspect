<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Connectors\ConnectionFactory;

/**
 * Class AspectTestCase
 */
class AspectTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Illuminate\Container\Container $app */
    protected $app;

    protected function setUp()
    {
        $this->createApplicationContainer();
    }

    /**
     * @return \Illuminate\Config\Repository
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function registerConfigure()
    {
        $filesystem = new \Illuminate\Filesystem\Filesystem;

        $this->app['config']->set(
            "ytake-laravel-aop",
            $filesystem->getRequire(__DIR__ . '/config/ytake-laravel-aop.php')
        );
        $this->app['config']->set(
            "database",
            $filesystem->getRequire(__DIR__ . '/config/database.php')
        );
        $this->app['config']->set(
            "cache",
            $filesystem->getRequire(__DIR__ . '/config/cache.php')
        );
        $this->app['files'] = $filesystem;
    }

    protected function registerDatabase()
    {
        Model::clearBootedModels();
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });
        $this->app->alias('db', DatabaseManager::class);
        $this->app->bind('Illuminate\Database\ConnectionResolverInterface', DatabaseManager::class);
    }

    protected function registerCache()
    {
        $this->app->singleton('cache', function ($app) {
            return new \Illuminate\Cache\CacheManager($app);
        });

        $this->app->singleton('cache.store', function ($app) {
            return $app['cache']->driver();
        });
        $this->app->alias('cache', \Illuminate\Contracts\Cache\Factory::class);
    }

    protected function registerAnnotationReader()
    {
        $this->app->singleton('aspect.annotation.reader', function ($app) {
            return (new \Ytake\LaravelAspect\AnnotationManager($app))->getReader();
        });
    }

    protected function createApplicationContainer()
    {
        $this->app = new \Illuminate\Container\Container;
        $this->app->singleton('config', function () {
            return new \Illuminate\Config\Repository;
        });
        $this->app->instance('log', $log = new \Illuminate\Log\Writer(
            new \Monolog\Logger('testing'))
        );
        $this->app->instance('Psr\Log\LoggerInterface', $log = new \Illuminate\Log\Writer(
            new \Monolog\Logger('testing'))
        );
        $this->registerConfigure();
        $this->registerDatabase();
        $this->registerCache();
        $this->registerAnnotationReader();
        $this->app->singleton('aspect.manager', function ($app) {
            return new \Ytake\LaravelAspect\AspectManager($app);
        });
        $this->app->bind(
            \Illuminate\Container\Container::class,
            function () {
                return $this->app;
            }
        );
        \Illuminate\Container\Container::setInstance($this->app);
    }

    protected function tearDown()
    {
        $this->app = null;
    }
}
