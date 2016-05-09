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
namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Contracts\Container\Container;
use Ytake\LaravelAspect\Annotation\Async;
use Ytake\LaravelAspect\Interceptor\AsyncInterceptor;

/**
 * Class AsyncPointCut
 */
class AsyncPointCut extends CommonPointCut implements PointCutable
{
    /** @var string */
    protected $annotation = Async::class;

    /**
     * @param Container $app
     *
     * @return \Ray\Aop\Pointcut
     */
    public function configure(Container $app)
    {
        $this->setInterceptor(new AsyncInterceptor);

        return $this->withAnnotatedAnyInterceptor($app);
    }

    /**
     * {@inheritdoc}
     */
    protected function withAnnotatedAnyInterceptor(Container $app)
    {
        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$this->interceptor]
        );
    }
}
