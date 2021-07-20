<?php

/**
 * AspectMergeTest.php
 */
class AspectMergeTest extends \AspectTestCase
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

    public function testCacheAspects()
    {
        /** @var \__Test\AspectMerge $cache */
        $cache = $this->app->make(\__Test\AspectMerge::class);
        $cache->caching(1);
        $result = $this->app['cache']->tags(['testing1', 'testing2'])->get('caching:1');
        $this->assertNull($result);
    }

    /**
     *
     */
    protected function resolveManager()
    {
        /** @var \Bssd\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->weave();
    }
}
