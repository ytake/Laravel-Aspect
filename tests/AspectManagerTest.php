<?php

class AspectManagerTest extends \TestCase
{
    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
    }

    public function testCreateDriverInstance()
    {
        $this->assertInternalType('string', $this->manager->getDefaultDriver());
    }

    public function testCreateGoDriverInstance()
    {
        $this->assertInstanceOf(
            \Ytake\LaravelAspect\GoAspect::class, $this->manager->driver('go')
        );
    }

    public function testCreateNullDriverInstance()
    {
        $driver = $this->manager->driver('none');
        $this->assertInstanceOf(\Ytake\LaravelAspect\NullAspect::class, $driver);
        $this->assertNull($driver->setAspects([]));
        $this->assertNull($driver->register());
        $class = new \ReflectionClass($driver);
        $this->assertSame(0, count($class->getProperties()));
    }
}
