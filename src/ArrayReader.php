<?php

namespace Ytake\LaravelAspect;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class ArrayReader
 */
class ArrayReader implements AnnotationReadable
{
    /**
     * @return AnnotationReader
     */
    public function getReader()
    {
        return new AnnotationReader();
    }
}
