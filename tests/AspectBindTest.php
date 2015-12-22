<?php

/**
 * Class AspectBindTest
 */
class AspectBindTest extends TestCase
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $file;

    /** @var string  */
    private $dir = __DIR__ . '/storage/tmp';

    public function setUp()
    {
        parent::setUp();
        $this->file = $this->app['files'];
    }

    public function testShouldReturnNoCacheableBindInstance()
    {
        $bind = new \Ytake\LaravelAspect\AspectBind(
            $this->file,
            false,
            $this->dir
        );
        $this->assertInstanceOf(\Ray\Aop\Bind::class, $bind->bind(StubBindableClass::class, []));
        $this->assertFalse($this->file->exists(__DIR__ . '/storage/tmp'));
    }

    public function testShouldReturnCacheableBindInstance()
    {
        $bind = new \Ytake\LaravelAspect\AspectBind(
            $this->file,
            true,
            $this->dir
        );
        $this->assertInstanceOf(\Ray\Aop\Bind::class, $bind->bind(StubBindableClass::class, []));
        $this->assertTrue($this->file->exists(__DIR__ . '/storage/tmp'));
    }

    public function tearDown()
    {
        $this->file->deleteDirectory($this->dir);
        parent::tearDown();
    }
}

/**
 * Class StubBindableClass
 */
class StubBindableClass
{

}