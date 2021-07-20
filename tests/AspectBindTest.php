<?php

/**
 * Class AspectBindTest
 */
class AspectBindTest extends AspectTestCase
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $file;

    public function setUp(): void
    {
        parent::setUp();
        $this->file = $this->app['files'];
    }

    public function testShouldReturnNoCacheableBindInstance()
    {
        $bind = new \Bssd\LaravelAspect\AspectBind(
            $this->file,
            $this->getDir(),
            false
        );
        $this->assertInstanceOf(\Ray\Aop\Bind::class, $bind->bind(StubBindableClass::class, []));
        $this->assertFalse($this->file->exists($this->getDir()));
    }

    public function testShouldReturnCacheableBindInstance()
    {
        $bind = new \Bssd\LaravelAspect\AspectBind(
            $this->file,
            $this->getDir(),
            true
        );
        $this->assertInstanceOf(\Ray\Aop\Bind::class, $bind->bind(StubBindableClass::class, []));
        $this->assertTrue($this->file->exists($this->getDir()));
    }

    public function tearDown(): void
    {
        $this->file->deleteDirectory($this->getDir());
        parent::tearDown();
    }

    /**
     * @return string
     */
    protected function getDir()
    {
        return  __DIR__ . '/storage/tmp';
    }
}

/**
 * Class StubBindableClass
 */
class StubBindableClass
{

}