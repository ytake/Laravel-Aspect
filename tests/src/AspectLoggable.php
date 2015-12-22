<?php

/**
 * for test
 */
namespace __Test;

use Ytake\LaravelAspect\Annotation\Loggable;

class AspectLoggable
{
    /**
     * @Loggable
     * @param null $id
     * @return null
     */
    public function normalLog($id = null)
    {
        return $id;
    }
}
