<?php

class AspectManagerTest extends \AspectTestCase
{
    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
    }

    public function testCreateDriverInstance()
    {
        $this->assertIsString($this->manager->getDefaultDriver());
    }

    public function testCreateGoDriverInstance()
    {
        $this->assertInstanceOf(
            \Bssd\LaravelAspect\RayAspectKernel::class, $this->manager->driver('ray')
        );
    }

    public function testCreateNullDriverInstance()
    {
        /** @var \Bssd\LaravelAspect\NullAspectKernel $driver */
        $driver = $this->manager->driver('none');
        $this->assertInstanceOf(\Bssd\LaravelAspect\NullAspectKernel::class, $driver);
        $this->assertNull($driver->register());
        $class = new \ReflectionClass($driver);
        $this->assertSame(0, count($class->getProperties()));
        $this->assertNull($driver->weave());
    }
}
