<?php

namespace Ytake\LaravelAspect;

use Ytake\LaravelAspect\Modules\AspectModule;

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
    public function register(AspectModule $module = null)
    {

    }
}
