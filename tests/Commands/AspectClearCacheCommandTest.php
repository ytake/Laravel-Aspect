<?php

/**
 * Class AspectClearCacheCommandTest
 */
class AspectClearCacheCommandTest extends \AspectTestCase
{
    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    /** @var \Bssd\LaravelAspect\Console\ClearCacheCommand */
    protected $command;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();

        $this->command = new \Bssd\LaravelAspect\Console\ClearCacheCommand(
            $this->app['config'],
            $this->app['files']
        );
        $this->command->setLaravel(new MockApplication());
    }

    public function testCacheClearFile()
    {
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $cache->namedMultipleNameAndKey(1000, 'testing');

        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            $output
        );
        $this->assertSame('aspect code cache clear!', trim($output->fetch()));

        $configure = $this->app['config']->get('ytake-laravel-aop');
        $driverConfig = $configure['aspect']['drivers'][$configure['aspect']['default']];
        if (isset($driverConfig['cache_dir'])) {
            $files = $this->app['files']->files($driverConfig['cache_dir']);
            $this->assertCount(0, $files);
        }
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
    }
}
