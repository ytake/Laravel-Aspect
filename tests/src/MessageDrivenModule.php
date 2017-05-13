<?php

namespace __Test;

/**
 * Class MessageDrivenModule
 */
class MessageDrivenModule extends \Ytake\LaravelAspect\Modules\MessageDrivenModule
{
    /** @var array  */
    protected $classes = [
        AspectMessageDriven::class,
    ];
}
