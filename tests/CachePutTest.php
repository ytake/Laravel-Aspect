<?php

class CachePutTest extends \AspectTestCase
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

    public function testCacheableGenerateCacheNameSingleKey()
    {
        $this->expectException(\InvalidArgumentException::class);
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
        $aspect->weave();
    }
}
