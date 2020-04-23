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

namespace Ytake\LaravelAspect\PointCut;

use Illuminate\Contracts\Container\Container;
use Ray\Aop\Pointcut;
use Ytake\LaravelAspect\Annotation\LogExceptions;
use Ytake\LaravelAspect\Interceptor\LogExceptionsInterceptor;

/**
 * Class LogExceptionsPointCut
 */
class LogExceptionsPointCut extends CommonPointCut implements PointCutable
{
    /** @var string */
    protected $annotation = LogExceptions::class;

    /**
     * @param Container $app
     *
     * @return \Ray\Aop\Pointcut
     */
    public function configure(Container $app): Pointcut
    {
        $interceptor = new LogExceptionsInterceptor;
        $interceptor->setLogger($app['Psr\Log\LoggerInterface']);
        $this->setInterceptor($interceptor);

        return $this->withAnnotatedAnyInterceptor();
    }
}
