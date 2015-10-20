<?php

namespace Ytake\LaravelAspect;

use Ytake\LaravelAspect\AspectRegisterable;

/**
 * Interface AspectDriverInterface
 */
interface AspectDriverInterface
{
    /**
     * @param AspectRegisterable $module
     */
    public function register(AspectRegisterable $module = null);
}
