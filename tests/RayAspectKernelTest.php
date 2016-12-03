<?php

class RayAspectKernelTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\RayAspectKernel */
    protected $kernel;
    public function setUp()
    {
        parent::setUp();
        $aspectConfigure = $this->app['config']->get('ytake-laravel-aop.aspect.drivers');
        $this->kernel = new \Ytake\LaravelAspect\RayAspectKernel(
            $this->app,
            $this->app['files'],
            $aspectConfigure['ray']
        );
    }

    /**
     * @expectedException \Ytake\LaravelAspect\Exception\ClassNotFoundException
     * @expectedExceptionMessage class not found at path: NotFoundClass
     */
    public function testExceptionCaseNotFoundClassRegister()
    {
        $this->kernel->register('NotFoundClass');
    }

    /**
     * @expectedException \Exception
     */
    public function testShouldThrowExceptionNotFoundClass()
    {
        $this->kernel->register(StubLoggableModule::class);
        $this->kernel->register(StubTransactionalModule::class);
        $this->kernel->weave();
    }
}

/**
 * Class StubLoggableModule
 */
class StubLoggableModule extends \Ytake\LaravelAspect\Modules\LoggableModule
{
    protected $classes = [
        'testing',
    ];
}

/**
 * Class StubTransactionalModule
 */
class StubTransactionalModule extends \Ytake\LaravelAspect\Modules\TransactionalModule
{
    protected $classes = [
        'testing'
    ];
}
