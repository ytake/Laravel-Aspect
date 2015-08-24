<?php

class CacheableTest extends \TestCase
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
        $cache = new \__Test\AspectCacheable;
        $result = $cache->singleKey(1000);
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('singleKey:1000'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCacheableGenerateCacheNameMultipleKey()
    {
        $cache = new \__Test\AspectCacheable;
        $result = $cache->multipleKey(1000, 'testing');
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('multipleKey:1000:testing'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCacheableCacheNameMultipleKey()
    {
        $cache = new \__Test\AspectCacheable;
        $result = $cache->namedMultipleKey(1000, 'testing');
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('testing1:1000:testing'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCacheableCacheNameMultipleNameAndKey()
    {
        $cache = new \__Test\AspectCacheable;
        $result = $cache->namedMultipleNameAndKey(1000, 'testing');
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('testing1:testing2:1000:testing'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testCacheableCacheObject()
    {
        $cache = new \__Test\AspectCacheable;
        $class = new \stdClass;
        $class->title = 'testing';
        $result = $cache->cachingKeyObject(1000, $class);
        $this->assertSame(1000, $result);
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
