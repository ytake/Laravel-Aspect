<?php

/**
 * Class ResolveInstanceTest
 */
class ResolveInstanceTest extends AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();

        $this->app->bind('a', ResolveMockClass::class);

        $this->app->bind(ResolveMockInterface::class, ResolveMockClass::class);

        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testShouldReturnBindingConcreteClass()
    {
        $resolve = $this->app->make(ResolveMockInterface::class);
        $resolve->get();
        $this->assertSame(
            $resolve->get(),
            $this->app['cache']->get('testing.resolve.instance')
        );
        $this->assertInstanceOf(get_class($resolve), $this->app->make('a'));
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->dispatch();
    }
}

interface ResolveMockInterface
{

}

class ResolveMockClass implements ResolveMockInterface
{
    /**
     * @\Ytake\LaravelAspect\Annotation\Cacheable(
     *     cacheName="testing.resolve.instance",
     *     driver="array"
     * )
     * @return int
     */
    public function get()
    {
        return time();
    }
}