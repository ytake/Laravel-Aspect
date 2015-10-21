<?php

namespace Ytake\LaravelAspect;

use Illuminate\Support\Manager;

/**
 * Class AnnotationManager
 * @method \Doctrine\Common\Annotations\Reader getReader() getReader
 */
class AnnotationManager extends Manager
{
    /**
     * default annotation reader(no caching other than in memory [in php arrays])
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'array';
    }

    /**
     * @return ArrayReader
     */
    protected function createArrayDriver()
    {
        return new ArrayReader();
    }

    /**
     * @return FileReader
     */
    protected function createFileDriver()
    {
        return new FileReader($this->getConfigure('file'));
    }

    /**
     * @param string $driver
     * @return string[]
     */
    protected function getConfigure($driver)
    {
        $annotationConfigure = $this->app['config']->get('ytake-laravel-aop.annotation.drivers');

        return $annotationConfigure[$driver];
    }
}
