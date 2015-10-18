<?php

namespace Ytake\LaravelAspect\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Transactional extends Annotation
{
    /** @var null $connection  database connection */
    public $value = null;
}
