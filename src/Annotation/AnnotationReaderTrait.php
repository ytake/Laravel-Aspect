<?php

namespace Ytake\LaravelAspect\Annotation;

use Doctrine\Common\Annotations\Reader;

/**
 * Class AnnotationReaderTrait
 */
trait AnnotationReaderTrait
{
    /** @var Reader */
    protected $reader;

    /** @var string */
    protected $annotation;

    /**
     * @param Reader $reader
     */
    public function setReader(Reader $reader)
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
