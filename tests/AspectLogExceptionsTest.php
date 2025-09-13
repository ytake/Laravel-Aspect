<?php

/**
 * Class AspectLogExceptionsTest
 */
class AspectLogExceptionsTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    /** @var Illuminate\Log\Writer */
    protected $log;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $file;

    protected function setUp(): void
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

    /**
     */
    public function testDefaultLogger()
    {
        $this->expectException(\Exception::class);
        // $this->log->useFiles($this->getDir() . '/.testing.exceptions.log');
        /** @var \__Test\AspectLoggable $cache */
        $cache = $this->app->make(\__Test\AspectLogExceptions::class);
        $cache->normalLog(1);
        $this->app['files']->deleteDirectory($this->getDir());
    }

    public function testShouldBeLogger()
    {
        /** @var \__Test\AspectLogExceptions $cache */
        $cache = $this->app->make(\__Test\AspectLogExceptions::class);
        
        // Clear any existing log
        $logFile = __DIR__ . '/logs/laravel.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        try {
            $cache->normalLog(1);
        } catch (\Exception $e) {
            $put = file_get_contents($logFile);
            $this->assertStringContainsString('LogExceptions:__Test\AspectLogExceptions.normalLog', $put);
            $this->assertStringContainsString('"code":0,"error_message":"', $put);
        }
        $this->app['files']->deleteDirectory($this->getDir());
    }

    public function testNoException()
    {
        /** @var \__Test\AspectLogExceptions $cache */
        $cache = $this->app->make(\__Test\AspectLogExceptions::class);
        $this->assertSame(1, $cache->noException());
    }

    public function testExpectException()
    {
        /** @var \__Test\AspectLogExceptions $cache */
        $cache = $this->app->make(\__Test\AspectLogExceptions::class);
        
        // Clear any existing log
        $logFile = __DIR__ . '/logs/laravel.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        try {
            $cache->expectException();
        } catch (\LogicException $e) {
            $put = file_get_contents($logFile);
            $this->assertStringContainsString('LogExceptions:__Test\AspectLogExceptions.expectException', $put);
            $this->assertStringContainsString('"code":0,"error_message":"', $put);
        }
        $this->app['files']->deleteDirectory($this->getDir());
    }

    public function testShouldNotPutExceptionLoggerFile()
    {
        // $this->log->useFiles($this->getDir() . '/.testing.exceptions.log');
        /** @var \__Test\AspectLogExceptions $logger */
        $logger = $this->app->make(\__Test\AspectLogExceptions::class);
        try {
            $logger->expectNoException();
        } catch (\Ytake\LaravelAspect\Exception\FileNotFoundException $e) {
            $this->assertFileDoesNotExist($this->getDir() . '/.testing.exceptions.log');
        }
        $this->app['files']->deleteDirectory($this->getDir());
    }

    public function testShouldNotThrowableException()
    {
        /** @var \__Test\AspectLogExceptions $logger */
        $logger = $this->app->make(\__Test\AspectLogExceptions::class);
        $this->assertSame(1, $logger->noException());
    }

    /**
     *
     */
    protected function resolveManager()
    {
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\LogExceptionsModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->weave();
    }

    /**
     * @return string
     */
    protected function getDir()
    {
        return __DIR__ . '/storage/log';
    }
}
