<?php

/**
 * Class RecursiveImplodeTest
 */
class RecursiveImplodeTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldReturnImplodedString()
    {
        $invoke = $this->reflectionInvoke([1, 2, 3]);
        $this->assertSame($invoke, '1:2:3');
        $invoke = $this->reflectionInvoke([1, 2, 3, [4,5, [6,7]]]);
        $this->assertSame($invoke, '1:2:3:4:5:6:7');
        $invoke = $this->reflectionInvoke([1, 2, 3, [4,5, [6,7], [new stdClass]]]);
        $this->assertSame($invoke, '1:2:3:4:5:6:7:stdClass');
        $invoke = $this->reflectionInvoke([1, 2, 3, [4,5, [6,7], [new stdClass, function () {}]]]);
        $this->assertSame($invoke, '1:2:3:4:5:6:7:stdClass:Closure');
    }

    /**
     * @param mixed $arguments
     * @return mixed
     */
    private function reflectionInvoke($arguments)
    {
        $interceptor = new StubCacheInterceptor;
        $reflectionMethod = new ReflectionMethod('StubCacheInterceptor', 'recursiveImplode');
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invoke($interceptor, ':', $arguments);
    }
}

final class StubCacheInterceptor extends \Bssd\LaravelAspect\Interceptor\AbstractCache
{
    public function invoke(\Ray\Aop\MethodInvocation $invocation)
    {
        // TODO: Implement invoke() method.
    }
}
