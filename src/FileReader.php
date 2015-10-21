<?php

namespace Ytake\LaravelAspect;

use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Class FileReader
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
     * @return CachedReader
     */
    public function getReader()
    {
        return new CachedReader(
            new AnnotationReader(),
            new FilesystemCache($this->config['cache_dir']),
            $this->config['debug']
        );
    }
}
