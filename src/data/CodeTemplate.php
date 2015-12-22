<?php

/**
 * CodeGenTemplate code-gen template
 *
 * Compiler takes only the statements in the method. Then create new inherit code with interceptors.
 *
 * @see http://paul-m-jones.com/archives/182
 * @see http://stackoverflow.com/questions/8343399/calling-a-function-with-explicit-parameters-vs-call-user-func-array
 * @see http://stackoverflow.com/questions/1796100/what-is-faster-many-ifs-or-else-if
 * @see http://stackoverflow.com/questions/2401478/why-is-faster-than-in-php
 *
 */
class CodeTemplate extends \Ray\Aop\FakeMock implements Ray\Aop\WeavedInterface
{
    /**
     * @var bool
     */
    private $isIntercepting = true;

    /**
     * @var array
     *
     * [$methodName => [$interceptorA[]][]
     */
    public $bindings;

    /**
     * Method Template
     */
    public function returnSame($a)
    {
        if (isset($this->bindings[__FUNCTION__]) === false) {
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }

        if ($this->isIntercepting === false) {
            $this->isIntercepting = true;
            return call_user_func_array('parent::' . __FUNCTION__, func_get_args());
        }

        $this->isIntercepting = false;
        $invocationResult = (new \Ray\Aop\ReflectiveMethodInvocation(
            $this,
            new \ReflectionMethod($this, __FUNCTION__),
            new \Ray\Aop\Arguments(func_get_args()),
            $this->bindings[__FUNCTION__]
        ))->proceed();
        $this->isIntercepting = true;

        return $invocationResult;
    }
}
