<?php

/**
 * Class ResolveInstanceTest
 */
class ResolveInstanceTest extends AspectTestCase
{
    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testShouldReturnBindingConcreteClass()
    {
        $this->app->bind('a', ResolveMockClass::class);

        $this->app->bind(ResolveMockInterface::class, ResolveMockClass::class);

        $resolve = $this->app->make(ResolveMockInterface::class);
        $resolve->get();
        $this->assertSame(
            $resolve->get(),
            $this->app['cache']->get('testing.resolve.instance')
        );
        $this->assertInstanceOf(get_class($resolve), $this->app->make('a'));
    }

    public function testShouldReturnSameInstanceForShared()
    {
        $this->app->singleton(ResolveMockInterface::class, ResolveMockClass::class);
        $resolve = $this->app->make(ResolveMockInterface::class);
        $this->assertSame($resolve, $this->app->make(ResolveMockInterface::class));
    }

    public function testShouldResolveContextualBinding()
    {
        $log = $this->app['Psr\Log\LoggerInterface'];
        if (!$this->app['files']->exists($this->getDir())) {
            $this->app['files']->makeDirectory($this->getDir());
        }
        $this->app->when(\__Test\AspectContextualBinding::class)
            ->needs(ResolveMockInterface::class)
            ->give(ResolveMockClass::class);
        /** @var  \Bssd\LaravelAspect\AspectManager $aspectManager */
        $aspectManager = $this->app['aspect.manager'];
        $driver = $aspectManager->driver('ray');
        $driver->register(\__Test\CacheableModule::class);
        $driver->register(\__Test\LoggableModule::class);
        $driver->weave();
        /** @var \__Test\AspectContextualBinding $concrete */
        $concrete = $this->app->make(\__Test\AspectContextualBinding::class);
        $result = $concrete->testing();
        sleep(1);
        $this->assertSame($result, $concrete->testing());
        $this->app['files']->deleteDirectory($this->getDir());
    }

    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->register(\__Test\LoggableModule::class);
        $aspect->weave();
    }

    /**
     * @return string
     */
    protected function getDir()
    {
        return  __DIR__ . '/storage/log';
    }
}
