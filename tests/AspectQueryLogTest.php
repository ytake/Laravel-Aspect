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

    public function testDefaultLogger()
    {
        $this->log->useFiles($this->logDir() . '/.testing.log');
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        $concrete->start();
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertContains('testing.INFO: QueryLog:__Test\AspectQueryLog.start', $put);
        $this->assertContains('SELECT date(\'now\')', $put);
    }

    public function testTransactionalLogger()
    {
        $this->log->useFiles($this->logDir() . '/.testing.log');
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        $concrete->multipleDatabaseAppendRecord();
        $put = $this->app['files']->get($this->logDir() . '/.testing.log');
        $this->assertContains('testing.INFO: QueryLog:__Test\AspectQueryLog.multipleDatabaseAppendRecord', $put);
        $this->assertContains('"queries":[{"query":"CREATE TABLE tests (test varchar(255) NOT NULL)"', $put);
    }

    /**
     * @expectedException \Exception
     */
    public function testExceptionalDatabaseLogger()
    {
        $this->log->useFiles($this->logDir() . '/.testing.log');
        /** @var AspectQueryLog $concrete */
        $concrete = $this->app->make(AspectQueryLog::class);
        $concrete->appendRecord(['test' => 'testing']);
    }

    public function tearDown()
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
