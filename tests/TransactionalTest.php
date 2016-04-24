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
        $this->app->bind(\__Test\AspectTransactionalDatabase::class, function () {
            return new \__Test\AspectTransactionalDatabase($this->app['db']);
        });
        $transactional = $this->app->make(\__Test\AspectTransactionalDatabase::class);
        $this->assertInternalType('array', $transactional->start());
        $this->assertInstanceOf('stdClass', $transactional->start()[0]);
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\TransactionalModule::class);
        $aspect->dispatch();
    }
}
