<?php

namespace Ytake\LaravelAspect\Aspect;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Illuminate\Database\QueryException;
use Illuminate\Database\ConnectionResolverInterface;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class AroundTransactionalAspect
 */
class AroundTransactionalAspect implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var ConnectionResolverInterface */
    protected $db;

    /**
     * @param ConnectionResolverInterface $db
     */
    public function __construct(ConnectionResolverInterface $db)
    {
        $this->db = $db;
    }

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
        $database = $this->db->connection($connection);
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
