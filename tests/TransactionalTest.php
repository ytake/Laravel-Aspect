<?php

class TransactionalTest extends \AspectTestCase
{

    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected static $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testTransactionalAssertString()
    {
        $transactional = $this->app->make(\__Test\AspectTransactionalString::class);
        $this->assertStringContainsString('testing', $transactional->start());
    }

    public function testTransactionalDatabase()
    {
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        $this->assertIsArray($transactional->start());
        $this->assertInstanceOf('stdClass', $transactional->start()[0]);
    }

    public function testTransactionalDatabaseThrowException()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        /** @var \__Test\AspectTransactionalDatabase $transactional */
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        try {
            $transactional->error();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertNull($this->app['db']->connection()->table("tests")->where('test', 'testing')->first());
        }
    }

    public function testTransactionalDatabaseThrowLogicException()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        /** @var \__Test\AspectTransactionalDatabase $transactional */
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        try {
            $transactional->errorException();
        } catch (\LogicException $e) {
            $this->assertNull($this->app['db']->connection()->table("tests")->where('test', 'testing')->first());
        }
    }

    public function testShouldReturnAppendRecord()
    {
        /** @var \__Test\AspectTransactionalDatabase $transactional */
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        // return method
        $this->assertTrue($transactional->appendRecord(['test' => 'testing']));
        $result = $this->app['db']->connection()->table("tests")->where('test', 'testing')->first();
        $this->assertObjectHasAttribute('test', $result);
    }

    public function testTransactionalMultipleDatabaseThrowException()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        /** @var \__Test\AspectTransactionalDatabase $transactional */
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        try {
            $transactional->multipleDatabaseAppendRecordException();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertNull($this->app['db']->connection()->table("tests")->where('test', 'testing')->first());
            $this->assertNull($this->app['db']->connection('testing_second')->table("tests")->where('test',
                'testing')->first());
        }
    }

    public function testShouldReturnStringTransactionalMultipleDatabase()
    {
        /** @var \__Test\AspectTransactionalDatabase $transactional */
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        $this->assertSame('transaction test', $transactional->multipleDatabaseAppendRecord());
        $result = $this->app['db']->connection()->table("tests")->where('test', 'testing')->first();
        $this->assertObjectHasAttribute('test', $result);
        $result = $this->app['db']->connection('testing_second')->table("tests")->where('test', 'testing second')->first();
        $this->assertObjectHasAttribute('test', $result);
    }

    /**
     *
     */
    protected function resolveManager()
    {
        /** @var \Bssd\LaravelAspect\AspectDriverInterface $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\TransactionalModule::class);
        $aspect->weave();
    }
}
