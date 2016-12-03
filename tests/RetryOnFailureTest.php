<?php

/**
 * Class RetryOnFailureTest
 */
class RetryOnFailureTest extends AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
    }

    public function testShouldThrowWithRetry()
    {
        /** @var \__Test\AspectRetryOnFailure $concrete */
        $concrete = $this->app->make(\__Test\AspectRetryOnFailure::class);
        try {
            $concrete->call();
        } catch (\Exception $e) {
            $this->assertSame(3, $concrete->counter);
            $concrete->counter = 0;
        }
        try {
            $concrete->call();
        } catch (\Exception $e) {
            $this->assertSame(3, $concrete->counter);
            $concrete->counter = 0;
        }

        try {
            $concrete->ignoreException();
        } catch (\Exception $e) {
            $this->assertSame(1, $concrete->counter);
        }
    }

    /**
     * @before
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\RetryOnFailureModule::class);
        $aspect->dispatch();
    }
}
