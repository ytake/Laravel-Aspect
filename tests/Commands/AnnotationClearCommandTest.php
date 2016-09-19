<?php

/**
 * Class AnnotationClearCommandTest
 */
class AnnotationClearCommandTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    /** @var \Ytake\LaravelAspect\Console\ClearAnnotationCacheCommand */
    protected $command;

    public function setUp()
    {
        parent::setUp();
        $this->command = new \Ytake\LaravelAspect\Console\ClearAnnotationCacheCommand(
            $this->app['config'],
            $this->app['files']
        );
        $this->command->setLaravel(new MockApplication());
    }

    public function testAnnotationCacheClearFile()
    {
        $this->app['config']->set('ytake-laravel-aop.annotation.default', 'file');
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
        $this->proceed();
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput(['driver' => 'file']),
            $output
        );
        $this->assertSame('annotation cache clear!', trim($output->fetch()));

        $configure = $this->app['config']->get('ytake-laravel-aop');
        $driverConfig = $configure['annotation']['drivers'][$configure['annotation']['default']];
        if (isset($driverConfig['cache_dir'])) {
            $files = $this->app['files']->files($driverConfig['cache_dir']);
            $this->assertCount(0, $files);
        }
    }

    public function testAnnotationCacheClearApcu()
    {
        $this->app['config']->set('ytake-laravel-aop.annotation.default', 'apcu');
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
        $this->proceed();
        $this->assertTrue(apcu_exists("[__Test\\AspectCacheable#cachingKeyObject@[Annot]][1]"));
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $this->command->run(
            new \Symfony\Component\Console\Input\ArrayInput(['driver' => 'apcu']),
            $output
        );
        $this->assertSame('annotation cache clear!', trim($output->fetch()));
        $this->assertFalse(apcu_exists("[__Test\\AspectCacheable#cachingKeyObject@[Annot]][1]"));
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\CacheableModule::class);
        $aspect->register(\__Test\CacheEvictModule::class);
        $aspect->dispatch();
    }

    /**
     * @return mixed
     */
    private function proceed()
    {
        /** @var \__Test\AspectCacheable $cache */
        $cache = $this->app->make(\__Test\AspectCacheable::class);
        $class = new \stdClass;
        $class->title = 'testing';
        return $cache->cachingKeyObject(1000, $class);
    }
}
