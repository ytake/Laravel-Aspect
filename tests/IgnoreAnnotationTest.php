<?php

/**
 * Class IgnoreAnnotationTest
 */
class IgnoreAnnotationTest extends AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testGenerateCacheNameRemoveNullKey()
    {
        /** @var \__Test\AnnotationStub $class */
        $class = $this->app->make(\__Test\AnnotationStub::class);
        $this->assertNull($class->testing());
    }


    /**
     *
     */
    protected function resolveManager()
    {
        /** @var \Ytake\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\LogExceptionsModule::class);
        $aspect->weave();
    }
}
