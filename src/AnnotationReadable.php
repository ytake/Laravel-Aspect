<?php

namespace Ytake\LaravelAspect;

use Doctrine\Common\Annotations\Reader;

/**
 * Interface AnnotationReadable
 */
interface AnnotationReadable
{
    /**
     * @return Reader
     */
    public function getReader();
}
