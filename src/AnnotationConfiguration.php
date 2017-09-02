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
 * Copyright (c) 2015-2017 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelAspect;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class AnnotationConfiguration
 */
class AnnotationConfiguration
{
    /** @var array */
    protected $configuration;

    /**
     * AnnotationConfiguration constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        $this->registerAnnotations();
    }

    /**
     * Add a new annotation to the globally ignored annotation names with regard to exception handling.
     */
    public function ignoredAnnotations()
    {
        if (isset($this->configuration['ignores'])) {
            $ignores = $this->configuration['ignores'];
            if (count($ignores)) {
                foreach ($ignores as $ignore) {
                    AnnotationReader::addGlobalIgnoredName($ignore);
                }
            }
        }
    }

    protected function registerAnnotations()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/RequireAnnotation.php');
    }
}
