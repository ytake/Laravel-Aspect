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
namespace Ytake\LaravelAspect\Matcher;

use Ray\Aop\AbstractMatcher;
use Doctrine\Common\Annotations\AnnotationReader;

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
        $count = 0;
        $annotation = $arguments[0];
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
     * {@inheritdoc}
     */
    public function matchesMethod(\ReflectionMethod $method, array $arguments)
    {
        $count = 0;
        $annotation = $arguments[0];
        $reflectionClass = new \ReflectionClass($method->class);
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
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
}
