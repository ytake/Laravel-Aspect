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

namespace Ytake\LaravelAspect\PointCut;

use Ray\Aop\Matcher;
use Ray\Aop\Pointcut;
use Illuminate\Container\Container;
use Ytake\LaravelAspect\Interceptor\CacheEvictInterceptor;

/**
 * Class CacheEvictExecution
 */
class CacheEvictPointCut implements PointCutable
{
    /** @var string  */
    protected $annotation = \Ytake\LaravelAspect\Annotation\CacheEvict::class;

    /**
     * @param Container $app
     *
     * @return Pointcut
     */
    public function configure(Container $app)
    {
        $cache = new CacheEvictInterceptor;
        $cache->setReader($app['aspect.annotation.reader']);
        $cache->setAnnotation($this->annotation);

        return new Pointcut(
            (new Matcher)->any(),
            (new Matcher)->annotatedWith($this->annotation),
            [$cache]
        );
    }
}
