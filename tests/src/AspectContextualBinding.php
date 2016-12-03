<?php

namespace __Test;

use Ytake\LaravelAspect\Annotation\Loggable;

/**
 * Class AspectContextualBinding
 *
 * for testing
 */
class AspectContextualBinding
{
    /** @var \ResolveMockInterface  */
    protected $resolveMock;

    /**
     * AspectContextualBinding constructor.
     *
     * @param \ResolveMockInterface $resolveMock
     */
    public function __construct(\ResolveMockInterface $resolveMock)
    {
        $this->resolveMock = $resolveMock;
    }

    /**
     * @Loggable
     * @return mixed
     */
    public function testing()
    {
        return $this->resolveMock->get();
    }
}