<?php

namespace Ytake\LaravelAspect;

/**
 * Interface AspectDriverInterface
 */
interface AspectDriverInterface
{
    /**
     * @return mixed
     */
    public function register();

    /**
     * add user aspect script
     */
    public function setAspects(array $classes);
}
