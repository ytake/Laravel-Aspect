<?php

class AspectManagerTest extends \TestCase
{
    /** @var \Ytake\LaravelAop\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAop\AspectManager($this->app);
    }

    public function testCreateDriverInstance()
    {
        $this->assertInternalType('string', $this->manager->getDefaultDriver());
    }

    public function testCreateGoDriverInstance()
    {
        $this->assertInstanceOf(
            \Ytake\LaravelAop\GoAspect::class, $this->manager->driver('go')
        );
    }
}
