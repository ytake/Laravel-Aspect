<?php

/**
 * for test
 */
namespace __Test;

use Bssd\LaravelAspect\Annotation\Loggable;

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

    /**
     * @Loggable(skipResult=true)
     * @param null $id
     * @return null
     */
    public function skipResultLog($id = null)
    {
        return $id;
    }
}
