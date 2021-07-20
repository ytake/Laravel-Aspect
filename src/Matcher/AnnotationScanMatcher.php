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

namespace Bssd\LaravelAspect\Matcher;

use Ray\Aop\AbstractMatcher;
use Doctrine\Common\Annotations\AnnotationReader;

use function func_get_args;

/**
 * Class AnnotationScanMatcher
 */
class AnnotationScanMatcher extends AbstractMatcher
{
    /** @var AnnotationReader */
    private $reader;

    /**
     * ScanMatcher constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->arguments = func_get_args();
        $this->reader = new AnnotationReader();
    }

    /**
     * {@inheritdoc}
     */
    public function matchesClass(\ReflectionClass $class, array $arguments)
    {
        return $this->has($class, $arguments[0]);
    }

    /**
     * @param \ReflectionClass $class
     * @param                  $annotation
     *
     * @return bool
     */
    private function has(\ReflectionClass $class, $annotation): bool
    {
        $count = 0;
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            $match = $this->reader->getMethodAnnotation($reflectionMethod, $annotation);
            if ($match) {
                $count++;
            }
        }
        if ($count > 1) {
            return false;
        }

        return true;
    }

    /**
     * @param \ReflectionMethod $method
     * @param array             $arguments
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function matchesMethod(\ReflectionMethod $method, array $arguments): bool
    {
        $class = new \ReflectionClass($method->class);

        return $this->has($class, $arguments[0]);
    }
}
