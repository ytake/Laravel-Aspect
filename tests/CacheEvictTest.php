<?php

class CacheEvictTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testGenerateCacheNameRemoveNullKey()
    {
        /** @var \__Test\AspectCacheEvict $cache */
        $cache = $this->app->make(\__Test\AspectCacheEvict::class);
        $cache->singleCacheDelete();
        $this->assertNull($this->app['cache']->get('singleCacheDelete'));
    }

    public function testCacheableAndRemove()
    {
        /** @var \__Test\AspectCacheEvict $cache */
        $cache = $this->app->make(\__Test\AspectCacheEvict::class);
        $cache->cached(1, 2);
        $this->assertNotNull($this->app['cache']->tags(['testing1'])->get('testing:1:2'));

        // flush all entries
        $cache->removeCache();
        $this->assertNull($this->app['cache']->tags(['testing1'])->get('testing:1:2'));
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->app['aspect.manager']->driver('ray');
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->weave();
    }
}
