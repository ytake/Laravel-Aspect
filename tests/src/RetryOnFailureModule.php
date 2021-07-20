<?php

namespace __Test;

/**
 * Class RetryOnFailureModule
 */
class RetryOnFailureModule extends \Bssd\LaravelAspect\Modules\RetryOnFailureModule
{
    /** @var array  */
    protected $classes = [
        AspectRetryOnFailure::class,
    ];
}
