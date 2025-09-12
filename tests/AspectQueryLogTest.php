<?php

use __Test\AspectQueryLog;

/**
 * Class AspectQueryLogTest
 */
class AspectQueryLogTest extends \AspectTestCase
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

    public function testDefaultLogger()
    {
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        
        // Clear any existing log
        $logFile = __DIR__ . '/logs/laravel.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        $concrete->start();
        
        $put = file_get_contents($logFile);
        $this->assertStringContainsString('INFO: QueryLog:__Test\AspectQueryLog.start', $put);
        $this->assertStringContainsString('SELECT date(\'now\')', $put);
    }

    public function testTransactionalLogger()
    {
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        
        // Clear any existing log
        $logFile = __DIR__ . '/logs/laravel.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        
        $concrete->multipleDatabaseAppendRecord();
        
        $put = file_get_contents($logFile);
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
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\LoggableModule::class);
        $aspect->register(\__Test\QueryLogModule::class);
        $aspect->register(\__Test\TransactionalModule::class);
        $aspect->weave();
    }
}
