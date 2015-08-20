<?php

class CacheableTest extends \TestCase
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
