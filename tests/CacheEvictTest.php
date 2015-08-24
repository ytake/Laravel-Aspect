<?php

class CacheEvictTest extends \TestCase
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

    /**
     * @runInSeparateProcess
     */
    public function testCacheableGenerateCacheNameSingleKey()
    {
        $cache = new \__Test\AspectCacheEvict();
        $result = $cache->singleCacheDelete();
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $annotation = new \Ytake\LaravelAspect\Annotation;
        $annotation->registerAspectAnnotations();
        /** @var \Ytake\LaravelAspect\GoAspect $aspect */
        $aspect = $this->manager->driver('go');
        $aspect->register();
    }
}
