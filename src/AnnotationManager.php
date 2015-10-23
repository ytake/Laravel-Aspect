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
 * Copyright (c) 2015 Yuuki Takezawa
 *
 *
 * CodeGenMethod Class, CodeGen Class is:
 * Copyright (c) 2012-2015, The Ray Project for PHP
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

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
        return $this->app['config']->get('ytake-laravel-aop.annotation.default');
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
