<?php

namespace Ytake\LaravelAspect;

/**
 * Class NullAspectKernel
 */
class NullAspectKernel implements AspectDriverInterface
{
    /**
     * @param null $module
     */
    public function register($module = null)
    {

    }
}
