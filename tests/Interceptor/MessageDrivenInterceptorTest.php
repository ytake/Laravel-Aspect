<?php
declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher;
use Bssd\LaravelAspect\Annotation\MessageDriven;
use Bssd\LaravelAspect\Interceptor\MessageDrivenInterceptor;

final class MessageDrivenInterceptorTest extends \AspectTestCase
{
    /** @var \Bssd\LaravelAspect\AspectManager $manager */
    protected $manager;

    /** @var  MessageDrivenInterceptor */
    private $interceptor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new \Bssd\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
        $this->interceptor = new MessageDrivenInterceptor;
    }

    public function testShouldDispatchMethod(): void
    {
        $this->expectOutputString('this');
        $this->interceptor->setBusDispatcher(
            $this->app->make(Dispatcher::class)
        );
        $this->interceptor->setAnnotation(MessageDriven::class);
        $this->interceptor->invoke(
            new StubMessageDrivenInvocation()
        );
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $aspect = $this->manager->driver('ray');
        $aspect->register(\__Test\MessageDrivenModule::class);
        $aspect->weave();
    }
}

class StubMessageDrivenInvocation implements \Ray\Aop\MethodInvocation
{
    /** @var ReflectionMethod */
    protected $reflectionMethod;

    public function getNamedArguments(): \ArrayObject
    {
        return new \ArrayObject([]);
    }

    public function getArguments(): \ArrayObject
    {
        return new \ArrayObject(['argument' => 'this']);
    }

    public function proceed()
    {
        return $this->intercept()->exec('this');
    }

    public function getThis()
    {
        return new \__Test\AspectMessageDriven();
    }

    /**
     * @return \Ray\Aop\ReflectionMethod
     * @throws ReflectionException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getMethod(): \Ray\Aop\ReflectionMethod
    {
        $reflectionClass = new \ReflectionClass(\__Test\AspectMessageDriven::class);
        $reflectionMethod = new \Ray\Aop\ReflectionMethod(\__Test\AspectMessageDriven::class, 'exec');
        $reflectionMethod->setObject(
            Container::getInstance()->make(\__Test\AspectMessageDriven::class, []),
            $reflectionClass->getMethod('exec')
        );

        return $reflectionMethod;
    }

    public function getName()
    {
        return $this->reflectionMethod->getName();
    }

    public function getAnnotation($name)
    {
        $reader = new \Doctrine\Common\Annotations\AnnotationReader();

        return $reader->getMethodAnnotation($this->reflectionMethod, $name);
    }

    protected function intercept()
    {
        return new \__Test\AspectMessageDriven;
    }
}
