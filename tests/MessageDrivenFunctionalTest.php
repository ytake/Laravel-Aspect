<?php

use __Test\LoggableModule;
use __Test\MessageDrivenModule;
use __Test\AspectMessageDriven;

/**
 * Class MessageDrivenFunctionalTest
 */
class MessageDrivenFunctionalTest extends AspectTestCase
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
        if (!$this->app['files']->exists($this->logDir())) {
            $this->app['files']->makeDirectory($this->logDir());
        }
    }

    public function testShouldBeLazyQueue()
    {
        $this->expectOutputString('this');
        /** @var AspectMessageDriven $concrete */
        $concrete = $this->app->make(AspectMessageDriven::class);
        
        // Clear any existing log
        $logFile = __DIR__ . '/logs/laravel.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        $concrete->exec('this');
        
        $put = file_get_contents($logFile);
        $this->assertStringContainsString('Loggable:__Test\AspectMessageDriven.exec {"args":{"param":"this"}', $put);
        $this->assertStringContainsString('Queued:__Test\AspectMessageDriven.logWith', $put);
    }

    public function testShouldBeEagerQueue()
    {
        /** @var AspectMessageDriven $concrete */
        $concrete = $this->app->make(AspectMessageDriven::class);
        
        // Clear any existing log
        $logFile = __DIR__ . '/logs/laravel.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        $concrete->eagerExec('testing');
        
        $put = file_get_contents($logFile);
        $this->assertStringContainsString('Queued:__Test\AspectMessageDriven.logWith', $put);
    }

    protected function resolveManager()
    {
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(MessageDrivenModule::class);
        $aspect->register(LoggableModule::class);
        $aspect->weave();
    }

    public function tearDown(): void
    {
        $this->app['files']->deleteDirectory($this->logDir());
        parent::tearDown();
    }
}
