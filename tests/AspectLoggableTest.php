<?php

/**
 * Class AspectLoggableTest
 */
class AspectLoggableTest extends \AspectTestCase
{
    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    /** @var Illuminate\Log\Writer */
    protected $log;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $file;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
        $this->log = $this->app['Psr\Log\LoggerInterface'];
        $this->file = $this->app['files'];
        if (!$this->app['files']->exists($this->logDir())) {
            $this->app['files']->makeDirectory($this->logDir());
        }
    }

    public function testDefaultLogger()
    {
        /** @var \__Test\AspectLoggable $cache */
        $cache = $this->app->make(\__Test\AspectLoggable::class);
        $cache->normalLog(1);
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertStringContainsString('Loggable:__Test\AspectLoggable.normalLog', $put);
        $this->assertStringContainsString('{"args":{"id":1},"result":1', $put);
    }

    public function testSkipResultLogger()
    {
        /** @var \__Test\AspectLoggable $cache */
        $cache = $this->app->make(\__Test\AspectLoggable::class);
        $cache->skipResultLog(1);
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertStringContainsString('Loggable:__Test\AspectLoggable.skipResultLog', $put);
        $this->assertStringNotContainsString('"result":1', $put);
    }

    public function tearDown(): void
    {
        $this->app['files']->deleteDirectory($this->logDir());
        parent::tearDown();
    }

    /**
     *
     */
    protected function resolveManager()
    {
        /** @var \Bssd\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\LoggableModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->weave();
    }
}
