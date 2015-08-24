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

    /**
     * @runInSeparateProcess
     */
    public function testCachePutReturnUpdatedValue()
    {
        $cache = new \__Test\AspectCachePut;
        $this->app['cache']->add('singleKey:1000', 1, 120);
        $result = $cache->singleKey(1000);
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('singleKey:1000'));
    }

    /**
     * @runInSeparateProcess
     * @expectedException \InvalidArgumentException
     */
    public function testCacheableGenerateCacheNameSingleKey()
    {
        $cache = new \__Test\AspectCachePut;
        $cache->throwExceptionCache();
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $annotation = new \Ytake\LaravelAspect\Annotation;
        $annotation->registerAspectAnnotations();
        /** @var \Ytake\LaravelAop\GoAspect $aspect */
        $aspect = $this->manager->driver('go');
        $aspect->register();
    }
}
