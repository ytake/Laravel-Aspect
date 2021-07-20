<?php

namespace __Test;

/**
 * Class MessageDrivenModule
 */
class MessageDrivenModule extends \Bssd\LaravelAspect\Modules\MessageDrivenModule
{
    /** @var array  */
    protected $classes = [
        AspectMessageDriven::class,
    ];
}
