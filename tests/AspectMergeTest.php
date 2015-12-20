<?php

/**
 * AspectMergeTest.php
 */
class AspectMergeTest extends \TestCase
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

    public function testA()
    {
        /** @var \__Test\AspectMerge $cache */
        $cache = $this->app->make(\__Test\AspectMerge::class);
        $cache->caching(1);
        $result = $this->app['cache']->tags(['testing1', 'testing2'])->get('caching:1');
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $annotation = new \Ytake\LaravelAspect\Annotation;
        $annotation->registerAspectAnnotations();
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->boot();
    }
}
