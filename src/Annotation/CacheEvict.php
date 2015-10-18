<?php

namespace Ytake\LaravelAspect\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class CacheEvict extends Annotation
{
    /** @var null|string[] $value cache key, if use array tagging */
    public $key = null;

    /** @var null */
    public $cacheName = null;

    /** @var string $driver cache driver */
    public $driver = null;

    /** @var array $tags */
    public $tags = [];

    /** @var bool  */
    public $allEntries = false;
}
