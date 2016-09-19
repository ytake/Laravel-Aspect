<?php

/**
 * Class AspectLoggableTest
 */
class AspectLoggableTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    /** @var Illuminate\Log\Writer */
    protected $log;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $file;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
        $this->log = $this->app['Psr\Log\LoggerInterface'];
        $this->file = $this->app['files'];
        if (!$this->app['files']->exists($this->getDir())) {
            $this->app['files']->makeDirectory($this->getDir());
        }
    }

    public function testDefaultLogger()
    {
        $this->log->useFiles($this->getDir() . '/.testing.log');
        /** @var \__Test\AspectLoggable $cache */
        $cache = $this->app->make(\__Test\AspectLoggable::class);
        $cache->normalLog(1);
        $put = $this->app['files']->get($this->getDir() . '/.testing.log');
        $this->assertContains('Loggable:__Test\AspectLoggable.normalLog', $put);
        $this->assertContains('{"args":{"id":1},"result":1', $put);
    }

    public function testSkipResultLogger()
    {
        $this->log->useFiles($this->getDir() . '/.testing.log');
        /** @var \__Test\AspectLoggable $cache */
        $cache = $this->app->make(\__Test\AspectLoggable::class);
        $cache->skipResultLog(1);
        $put = $this->app['files']->get($this->getDir() . '/.testing.log');
        $this->assertContains('Loggable:__Test\AspectLoggable.skipResultLog', $put);
        $this->assertNotContains('"result":1', $put);
    }

    public function tearDown()
    {
        $this->app['files']->deleteDirectory($this->getDir());
        parent::tearDown();
    }

    /**
     *
     */
    protected function resolveManager()
    {
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\LoggableModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->dispatch();
    }

    /**
     * @return string
     */
    protected function getDir()
    {
        return  __DIR__ . '/storage/log';
    }
}
