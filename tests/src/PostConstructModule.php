<?php

namespace __Test;

/**
 * Class PostConstructModule
 */
class PostConstructModule extends \Bssd\LaravelAspect\Modules\PostConstructModule
{
    protected $classes = [
        AspectPostConstruct::class,
    ];
}
