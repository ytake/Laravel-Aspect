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

    protected function setUp()
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
        $this->log->useFiles($this->logDir() . '/.testing.log');
        /** @var AspectMessageDriven $concrete */
        $concrete = $this->app->make(AspectMessageDriven::class);
        $concrete->exec('this');
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertContains('Loggable:__Test\AspectMessageDriven.exec {"args":{"param":"this"}', $put);
        $this->assertContains('Queued:__Test\AspectMessageDriven.logWith', $put);
    }

    public function testShouldBeEagerQueue()
    {
        $this->log->useFiles($this->logDir() . '/.testing.log');
        /** @var AspectMessageDriven $concrete */
        $concrete = $this->app->make(AspectMessageDriven::class);
        $concrete->eagerExec('testing');
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertContains('Queued:__Test\AspectMessageDriven.logWith', $put);
    }

    /**
     * @before
     */
    protected function resolveManager()
    {
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(MessageDrivenModule::class);
        $aspect->register(LoggableModule::class);
        $aspect->weave();
    }

    public function tearDown()
    {
        $this->app['files']->deleteDirectory($this->logDir());
        parent::tearDown();
    }
}
