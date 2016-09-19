<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2015-2016 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelAspect;

use Illuminate\Support\Manager;
use Doctrine\Common\Annotations\AnnotationReader;
use Ytake\LaravelAspect\Annotation\Reader\ApcuReader;
use Ytake\LaravelAspect\Annotation\Reader\FileReader;
use Ytake\LaravelAspect\Annotation\Reader\ArrayReader;

/**
 * Class AnnotationManager
 * @method \Doctrine\Common\Annotations\Reader getReader() getReader
 */
class AnnotationManager extends Manager
{
    /**
     * default annotation reader(no caching other than in memory [in php arrays])
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('ytake-laravel-aop.annotation.default');
    }

    /**
     * @return ArrayReader
     */
    protected function createArrayDriver()
    {
        $this->ignoredAnnotations($this->app['config']->get('ytake-laravel-aop.annotation.ignores', []));

        return new ArrayReader();
    }

    /**
     * @return FileReader
     */
    protected function createFileDriver()
    {
        $this->ignoredAnnotations($this->app['config']->get('ytake-laravel-aop.annotation.ignores', []));

        return new FileReader($this->getConfigure('file'));
    }

    /**
     * @return ApcuReader
     */
    protected function createApcuDriver()
    {
        $this->ignoredAnnotations($this->app['config']->get('ytake-laravel-aop.annotation.ignores', []));

        return new ApcuReader($this->getConfigure('file'));
    }

    /**
     * @param string $driver
     *
     * @return string[]
     */
    protected function getConfigure($driver)
    {
        $annotationConfigure = $this->app['config']->get('ytake-laravel-aop.annotation.drivers');
        $annotationConfigure[$driver]['debug'] = $this->app['config']->get('ytake-laravel-aop.annotation.debug', false);

        return $annotationConfigure[$driver];
    }

    /**
     * Add a new annotation to the globally ignored annotation names with regard to exception handling.
     *
     * @param array $ignores
     */
    private function ignoredAnnotations(array $ignores = [])
    {
        foreach ($ignores as $ignore) {
            AnnotationReader::addGlobalIgnoredName($ignore);
        }
    }
}
