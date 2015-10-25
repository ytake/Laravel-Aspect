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

    public function testCacheableGenerateCacheNameSingleKey()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $result = $cache->singleKey(1000);
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('singleKey:1000'));
        $this->assertSame($result, $cache->singleKey(1000));
    }

    public function testCacheableGenerateCacheNameMultipleKey()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $result = $cache->multipleKey(1000, 'testing');
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('multipleKey:1000:testing'));
    }

    public function testCacheableCacheNameMultipleKey()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $result = $cache->namedMultipleKey(1000, 'testing');
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->get('testing1:1000:testing'));
    }

    public function testCacheableCacheNameMultipleNameAndKey()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $result = $cache->namedMultipleNameAndKey(1000, 'testing');
        $this->assertSame(1000, $result);
        $this->assertSame(1000, $this->app['cache']->tags(['testing1', 'testing2'])->get('namedMultipleNameAndKey:1000:testing'));
    }

    public function testCacheableCacheObject()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
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
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
    }
}
