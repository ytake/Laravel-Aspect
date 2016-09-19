<?php

/**
 * Class AspectApcuCacheableTest
 */
class AspectApcuCacheableTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->app['config']->set('ytake-laravel-aop.annotation.default', 'apcu');
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testCacheableCacheObject()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $class = new \stdClass;
        $class->title = 'testing';
        $result = $cache->cachingKeyObject(1000, $class);
        $this->assertSame(1000, $result);
        $this->assertNotNull(apcu_exists("[__Test\\AspectCacheable#cachingKeyObject@[Annot]][1]"));
        apcu_clear_cache();
        $this->assertFalse(apcu_exists("[__Test\\AspectCacheable#cachingKeyObject@[Annot]][1]"));
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->dispatch();
    }
}
