<?php

/**
 * for test
 */
namespace __Test;

use Ytake\LaravelAspect\Annotation\LogExceptions;
use Ytake\LaravelAspect\Exception\FileNotFoundException;

class AspectLogExceptions
{
    /**
     * @LogExceptions
     * @param null $id
     * @return null
     * @throws \Exception
     */
    public function normalLog($id = null)
    {
        throw new \Exception;
        return $id;
    }

    /**
     * @LogExceptions(expect="\LogicException")
     */
    public function expectException()
    {
        throw new \LogicException;
    }

    /**
     * @LogExceptions(expect="\LogicException")
     */
    public function expectNoException()
    {
        throw new FileNotFoundException;
    }

    /**
     * @return int
     */
    public function noException()
    {
        return 1;
    }
}
