<?php

/**
 * for test
 */
namespace __Test;

use Bssd\LaravelAspect\Annotation\LogExceptions;
use Bssd\LaravelAspect\Exception\FileNotFoundException;

class AspectLogExceptions
{
    /**
     * @LogExceptions(driver="custom")
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
     * @LogExceptions(expect="\LogicException",driver="custom")
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
        throw new FileNotFoundException(__DIR__);
    }

    /**
     * @return int
     */
    public function noException()
    {
        return 1;
    }
}
