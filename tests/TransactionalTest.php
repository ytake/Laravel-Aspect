<?php

class TransactionalTest extends \AspectTestCase
{

    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected static $instance;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testTransactionalAssertString()
    {
        $transactional = $this->app->make(\__Test\AspectTransactionalString::class);
        $this->assertContains('testing', $transactional->start());
    }

    public function testTransactionalDatabase()
    {
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        $this->assertInternalType('array', $transactional->start());
        $this->assertInstanceOf('stdClass', $transactional->start()[0]);
    }

    /**
     * @expectedException \Illuminate\Database\QueryException
     */
    public function testTransactionalDatabaseThrowException()
    {
        /** @var \__Test\AspectTransactionalDatabase $transactional */
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        try {
            $transactional->error();
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertNull($this->app['db']->connection()->table("tests")->where('test', 'testing')->first());
        }
    }

    /**
     * @expectedException \Illuminate\Database\QueryException
     */
    public function testTransactionalDatabaseThrowLogicException()
    {
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

    /**
     * @expectedException \Illuminate\Database\QueryException
     */
    public function testTransactionalMultipleDatabaseThrowException()
    {
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
        /** @var \Ytake\LaravelAspect\AspectDriverInterface $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\TransactionalModule::class);
        $aspect->weave();
    }
}
