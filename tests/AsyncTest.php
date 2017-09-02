<?php

class AsyncTest extends \AspectTestCase
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

    public function testShouldReturnNullWithAsync()
    {
        /** @var \__Test\Async $class */
        $class = $this->app->make(\__Test\Async::class);
        $this->assertNull($class->save());
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\AsyncModule::class);
        $aspect->weave();
    }
}
