<?php

namespace Ytake\LaravelAspect\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Cacheable extends Annotation
{
    /** @var null|string[] $value cache key, if use array tagging */
    public $key = null;

    /** @var null */
    public $cacheName = null;

    /** @var string $driver cache driver */
    public $driver = null;

    /** @var int $lifetime cache life time */
    public $lifetime = 120;

    /** @var array $tags */
    public $tags = [];
}
