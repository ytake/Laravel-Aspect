<?php

namespace Ytake\LaravelAspect;

/**
 * Class NullAspectKernel
 */
class NullAspectKernel implements AspectDriverInterface
{
    /**
     * initialize aspect kernel
     *
     * @return void
     */
    public function register()
    {
        return;
    }

    /**
     * @param array $classes
     */
    public function setAspects(array $classes)
    {
        return;
    }
}
