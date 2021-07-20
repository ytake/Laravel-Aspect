<?php
declare(strict_types=1);

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
 * Copyright (c) 2015-2020 Yuuki Takezawa
 *
 */

namespace Bssd\LaravelAspect;

use Bssd\LaravelAspect\Annotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

use function count;
use function array_merge;

/**
 * Class AnnotationConfiguration
 */
class AnnotationConfiguration
{
    /** @var array */
    protected $configuration = [];

    /** @var array */
    protected $customAnnotations = [];

    /** @var string[] */
    private $annotations = [
        Annotation\Cacheable::class,
        Annotation\CacheEvict::class,
        Annotation\CachePut::class,
        Annotation\EagerQueue::class,
        Annotation\LazyQueue::class,
        Annotation\LogExceptions::class,
        Annotation\Loggable::class,
        Annotation\MessageDriven::class,
        Annotation\PostConstruct::class,
        Annotation\QueryLog::class,
        Annotation\RetryOnFailure::class,
        Annotation\Transactional::class,
    ];

    /**
     * @param array $configuration
     * @param array $customAnnotations
     */
    public function __construct(array $configuration, array $customAnnotations = [])
    {
        $this->configuration = $configuration;
        $this->customAnnotations = $customAnnotations;
        $this->registerAnnotations();
    }

    /**
     * Add a new annotation to the globally ignored annotation names with regard to exception handling.
     */
    public function ignoredAnnotations(): void
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

    protected function registerAnnotations(): void
    {
        $this->annotations = array_merge(
            $this->annotations,
            $this->customAnnotations
        );
        if (isset($this->configuration['custom'])) {
            $this->annotations = array_merge(
                $this->annotations,
                $this->configuration['custom']
            );
        }
        foreach ($this->annotations as $annotation) {
            AnnotationRegistry::loadAnnotationClass($annotation);
        }
    }
}
