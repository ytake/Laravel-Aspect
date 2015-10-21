<?php

namespace Ytake\LaravelAspect;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\AnnotationReader;

class CacheReader implements AnnotationReadable
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
            new ApcCache(),
            $this->config['debug']
        );
    }
}
