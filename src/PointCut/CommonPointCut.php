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

namespace Bssd\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Ray\Aop\MethodInterceptor;

/**
 * Class CommonPointCut
 */
class CommonPointCut
{
    /** @var MethodInterceptor */
    protected $interceptor;

    /** @var string */
    protected $annotation;

    /**
     * @param MethodInterceptor $interceptor
     */
    protected function setInterceptor(MethodInterceptor $interceptor): void
    {
        $this->interceptor = $interceptor;
    }

    /**
     * @return Pointcut
     */
    protected function withAnnotatedAnyInterceptor(): PointCut
    {
        if (method_exists($this->interceptor, 'setAnnotation')) {
            $this->interceptor->setAnnotation($this->annotation);
        }

        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$this->interceptor]
        );
    }
}
