<?php

class AspectManagerTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
    }

    public function testCreateDriverInstance()
    {
        $this->assertIsString($this->manager->getDefaultDriver());
    }

    public function testCreateGoDriverInstance()
    {
        $this->assertInstanceOf(
            \Ytake\LaravelAspect\RayAspectKernel::class, $this->manager->driver('ray')
        );
    }

    public function testCreateNullDriverInstance()
    {
        /** @var \Ytake\LaravelAspect\NullAspectKernel $driver */
        $driver = $this->manager->driver('none');
        $this->assertInstanceOf(\Ytake\LaravelAspect\NullAspectKernel::class, $driver);
        $this->assertNull($driver->register());
        $class = new \ReflectionClass($driver);
        $this->assertSame(0, count($class->getProperties()));
        $this->assertNull($driver->weave());
    }
}
