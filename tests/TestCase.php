<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\QueueEntityResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class TestCase extends \PHPUnit_Framework_TestCase
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
        $this->app['filesystem'] = $filesystem;
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
    }

    protected function createApplicationContainer()
    {
        $this->app = new \Illuminate\Container\Container;
        $this->app->singleton('config', function () {
            return new \Illuminate\Config\Repository;
        });
        $this->registerConfigure();
        $this->registerDatabase();
    }

    protected function tearDown()
    {
        $this->app['filesystem']->deleteDirectory('storage');
        $this->app = null;
    }
}
