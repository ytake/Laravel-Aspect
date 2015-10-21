<?php

namespace Ytake\LaravelAspect;

/**
 * Interface AspectDriverInterface
 */
interface AspectDriverInterface
{
    /**
     * @param null $module
     * @return void
     */
    public function register($module = null);
}
