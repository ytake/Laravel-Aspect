<?php

namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Illuminate\Database\QueryException;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class TransactionalInterceptor
 */
class TransactionalInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /**
     * @param MethodInvocation $invocation
     *
     * @return object
     */
    public function invoke(MethodInvocation $invocation)
    {
        $annotation = $this->reader
            ->getMethodAnnotation($invocation->getMethod(), $this->annotation);

        $connection = $annotation->value;
        $database = app('db')->connection($connection);
        $database->beginTransaction();
        try {
            $result = $invocation->proceed();
            $database->commit();

            return $result;
        } catch (QueryException $exception) {
            $database->rollBack();
            throw $exception;
        }
    }
}
