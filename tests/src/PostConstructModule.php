<?php

namespace __Test;

/**
 * Class PostConstructModule
 */
class PostConstructModule extends \Ytake\LaravelAspect\Modules\PostConstructModule
{
    protected $classes = [
        AspectPostConstruct::class,
    ];
}
