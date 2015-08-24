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
}
