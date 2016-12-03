<?php

namespace __Test;

/**
 * Class RetryOnFailureModule
 */
class RetryOnFailureModule extends \Ytake\LaravelAspect\Modules\RetryOnFailureModule
{
    /** @var array  */
    protected $classes = [
        AspectRetryOnFailure::class,
    ];
}
