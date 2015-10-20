<?php

namespace Ytake\LaravelAspect;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Ytake\LaravelAspect\Exception\FileNotFoundException;

/**
 * Class Annotation
 */
class Annotation
{
    /** @var string[] */
    protected $files = [];

    /**
     * @param array $files
     */
    public function registerAnnotations(array $files)
    {
        $this->files = $files;
    }

    /**
     * @throws FileNotFoundException
     */
    public function registerAspectAnnotations()
    {
        foreach ($this->files as $file) {
            if (!file_exists($file)) {
                throw new FileNotFoundException($file);
            }
            AnnotationRegistry::registerFile($file);
        }
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Transactional.php');
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Cacheable.php');
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/CacheEvict.php');
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/CachePut.php');
    }
}
