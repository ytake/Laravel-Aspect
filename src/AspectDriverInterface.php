<?php

namespace Ytake\LaravelAspect;

use Ytake\LaravelAspect\Modules\AspectModule;

/**
 * Interface AspectDriverInterface
 */
interface AspectDriverInterface
{
    /**
     * @param AspectModule $module
     */
    public function register(AspectModule $module = null);
}
