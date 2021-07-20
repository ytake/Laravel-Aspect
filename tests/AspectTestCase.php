<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Connectors\ConnectionFactory;

/**
 * Class AspectTestCase
 */
class AspectTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var \Illuminate\Container\Container $app */
    protected $app;

    protected function setUp(): void
    {
        $this->createApplicationContainer();
    }

    /**
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
        $this->app['config']->set(
            'queue',
            $filesystem->getRequire(__DIR__ . '/config/queue.php')
        );
        $this->app['config']->set(
            'logging',
            $filesystem->getRequire(__DIR__ . '/config/logging.php')
        );
        $this->app['config']->set(
            'app',
            [
                'key'    => 'base64:vL6wZyxF+/4DhgKiNoA3k80pwdX2VwvLDSig9juMk8g=',
                'cipher' => 'AES-256-CBC',
            ]
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

    protected function createApplicationContainer()
    {
        $this->app = new class() extends \Illuminate\Container\Container {
            public function storagePath()
            {
                return __DIR__;
            }
        };
        $this->app->singleton('config', function () {
            return new \Illuminate\Config\Repository;
        });
        $logManager = new \Illuminate\Log\LogManager($this->app);
        $this->app->instance('log', $logManager);
        $this->app->instance('Psr\Log\LoggerInterface', $logManager);
        $eventProvider = new \Illuminate\Events\EventServiceProvider($this->app);
        $eventProvider->register();
        $busServiceProvider = new \Illuminate\Bus\BusServiceProvider($this->app);
        $busServiceProvider->register();
        $queueServiceProvider = new \Illuminate\Queue\QueueServiceProvider($this->app);
        $queueServiceProvider->register();
        $encryptionServiceProvider = new \Illuminate\Encryption\EncryptionServiceProvider($this->app);
        $encryptionServiceProvider->register();
        $this->app->alias('queue', \Illuminate\Contracts\Queue\Factory::class);
        $this->app->alias('events', \Illuminate\Contracts\Events\Dispatcher::class);
        $this->registerConfigure();
        $this->registerDatabase();
        $this->registerCache();
        $annotationConfiguration = new \Bssd\LaravelAspect\AnnotationConfiguration(
            $this->app['config']->get('ytake-laravel-aop.annotation')
        );
        $annotationConfiguration->ignoredAnnotations();
        $this->app->singleton('aspect.manager', function ($app) {
            return new \Bssd\LaravelAspect\AspectManager($app);
        });
        $this->app->bind(
            \Illuminate\Contracts\Container\Container::class,
            \Illuminate\Container\Container::class
        );
        $this->app->bind(
            \Illuminate\Container\Container::class,
            function () {
                return $this->app;
            }
        );
        \Illuminate\Container\Container::setInstance($this->app);
    }

    /**
     * @return string
     */
    protected function logDir()
    {
        return __DIR__ . '/storage/log';
    }
}
