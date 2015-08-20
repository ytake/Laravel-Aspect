<?php

class CacheEvictTest extends \TestCase
{
    /** @var \Ytake\LaravelAop\AspectManager $manager */
    protected $manager;

    protected static $instance;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAop\AspectManager($this->app);
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
        $annotation = new \Ytake\LaravelAop\Annotation;
        $annotation->registerAspectAnnotations();
        /** @var \Ytake\LaravelAop\GoAspect $aspect */
        $aspect = $this->manager->driver('go');
        $aspect->register();
    }
}
