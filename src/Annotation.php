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
