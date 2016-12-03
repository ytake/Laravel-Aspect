<?php

namespace __Test;

use Ytake\LaravelAspect\Annotation\RetryOnFailure;

/**
 * Class AspectRetryOnFailure
 */
class AspectRetryOnFailure
{
    /** @var int */
    public $counter = 0;

    /**
     * @RetryOnFailure(
     *     types={
     *         \LogicException::class
     *     },
     *     attempts=3
     * )
     */
    public function call()
    {
        $this->counter += 1;
        throw new \LogicException;
    }

    /**
     * @RetryOnFailure(
     *     types={
     *         LogicException::class,
     *     },
     *     attempts=3,
     *     ignore=Exception::class
     * )
     */
    public function ignoreException()
    {
        $this->counter += 1;
        throw new \Exception;
    }
}
