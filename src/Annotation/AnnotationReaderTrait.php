<?php

namespace Ytake\LaravelAspect\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class AnnotationReaderTrait
 */
trait AnnotationReaderTrait
{
    /** @var AnnotationReader */
    protected $reader;

    /** @var string */
    protected $annotation;

    /**
     * @param AnnotationReader $reader
     */
    public function setReader(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $annotation
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
    }
}
