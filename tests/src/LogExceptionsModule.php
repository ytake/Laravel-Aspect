<?php

namespace __Test;

use Bssd\LaravelAspect\Modules\LogExceptionsModule as Loggable;

class LogExceptionsModule extends Loggable
{
    /**
     * @var array
     */
    protected $classes = [
        \__Test\AspectLogExceptions::class,
        \__Test\AnnotationStub::class
    ];
}
