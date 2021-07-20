<?php

use __Test\AspectQueryLog;

/**
 * Class AspectQueryLogTest
 */
class AspectQueryLogTest extends \AspectTestCase
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
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        $concrete->start();
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertStringContainsString('INFO: QueryLog:__Test\AspectQueryLog.start', $put);
        $this->assertStringContainsString('SELECT date(\'now\')', $put);
    }

    public function testTransactionalLogger()
    {
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        $concrete->multipleDatabaseAppendRecord();
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertStringContainsString('INFO: QueryLog:__Test\AspectQueryLog.multipleDatabaseAppendRecord', $put);
        $this->assertStringContainsString('"queries":[{"query":"CREATE TABLE tests (test varchar(255) NOT NULL)"', $put);
    }

    public function testExceptionalDatabaseLogger()
    {
        $this->expectException(\Exception::class);
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        $concrete->appendRecord(['test' => 'testing']);
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
        $aspect->register(\__Test\QueryLogModule::class);
        $aspect->register(\__Test\TransactionalModule::class);
        $aspect->weave();
    }
}
