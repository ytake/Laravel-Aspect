<?php

/**
 * Class PostConstructTest
 */
class PostConstructTest extends \AspectTestCase
{
    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    public function testShouldProceedPostConstructSumVariable()
    {
        /** @var \__Test\AspectPostConstruct $class */
        $class = $this->app->make(\__Test\AspectPostConstruct::class, ['a' => 1]);
        $this->assertInstanceOf(\__Test\AspectPostConstruct::class, $class);
        $this->assertSame(2, $class->getA());
    }

    protected function resolveManager()
    {
        /** @var \Bssd\LaravelAspect\RayAspectKernel $aspect */
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\PostConstructModule::class);
        $aspect->weave();
    }
}
