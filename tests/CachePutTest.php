<?php

class CachePutTest extends \TestCase
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

    public function testCachePutDefaultValues()
    {
        $cache = $this->app->make(\__Test\AspectCachePut::class);
        $this->app['cache']->add('singleKey:1000', 1, 120);
        $this->assertNull($cache->singleKey());
    }

    public function testCachePutReturnUpdatedValue()
    {
        $cache = $this->app->make(\__Test\AspectCachePut::class);
        $this->app['cache']->add('singleKey:1000', 1, 120);
        $result = $cache->singleKey(1000);
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('singleKey:1000'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCacheableGenerateCacheNameSingleKey()
    {
        $cache = $this->app->make(\__Test\AspectCachePut::class);
        $cache->throwExceptionCache();
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CachePutModule::class);
        $aspect->dispatch();
    }
}
