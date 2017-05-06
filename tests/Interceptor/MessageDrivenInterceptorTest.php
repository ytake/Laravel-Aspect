<?php

use Illuminate\Contracts\Bus\Dispatcher;
use Ytake\LaravelAspect\Annotation\MessageDriven;
use Ytake\LaravelAspect\Interceptor\MessageDrivenInterceptor;

/**
 * Class MessageDrivenInterceptorTest
 */
class MessageDrivenInterceptorTest extends \AspectTestCase
{
    /** @var  MessageDrivenInterceptor */
    private $interceptor;

    protected function setUp()
    {
        parent::setUp();
        $this->interceptor = new MessageDrivenInterceptor;
    }

    public function testShouldDispatchMethod()
    {
        $this->expectOutputString('this');
        $this->interceptor->setBusDispatcher(
            $this->app->make(Dispatcher::class)
        );
        $this->interceptor->setAnnotation(MessageDriven::class);
        $this->interceptor->invoke(new StubMessageDrivenInvocation());
    }
}

class StubMessageDrivenInvocation implements \Ray\Aop\MethodInvocation
{
    /** @var ReflectionMethod */
    protected $reflectionMethod;

    public function getArguments()
    {
        return new \Ray\Aop\Arguments(['argument' => 'this']);
    }

    public function proceed()
    {
        return $this->intercept()->exec('this');
    }

    public function getThis()
    {
        return new \__Test\AspectMessageDriven;
    }

    public function getMethod()
    {
        $reflectionClass = new \ReflectionClass(\__Test\AspectMessageDriven::class);
        $this->reflectionMethod = $reflectionClass->getMethod('exec');

        return $this;
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
