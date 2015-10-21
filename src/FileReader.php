<?php

namespace Ytake\LaravelAspect;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;

/**
 * Class FileReader
 *
 * @deprecated the FileCacheReader is deprecated and will be removed
 *             in version 2.0.0 of doctrine/annotations. Please use the
 *             {@see \Doctrine\Common\Annotations\CachedReader} instead.
 */
class FileReader implements AnnotationReadable
{
    /** @var string[] */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return FileCacheReader
     */
    public function getReader()
    {
        return new FileCacheReader(
            new AnnotationReader(),
            $this->config['cache_dir'],
            $this->config['debug']
        );
    }
}
