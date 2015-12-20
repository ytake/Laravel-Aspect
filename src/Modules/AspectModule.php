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

namespace Ytake\LaravelAspect\Modules;

use Ray\Aop\Bind;
use Ray\Aop\CompilerInterface;
use Illuminate\Contracts\Container\Container as Application;

/**
 * Class AspectModule
 */
abstract class AspectModule
{
    /** @var Application */
    protected $app;

    /** @var Bind */
    protected $bind;

    /** @var CompilerInterface */
    protected $compiler;

    protected static $pointcuts = [];

    /** @var array  */
    protected $resolve = [];

    /**
     * @param Application $app
     * @param Bind        $bind
     */
    public function __construct(Application $app, Bind $bind)
    {
        $this->app = $app;
        $this->bind = $bind;
    }

    /**
     * @return void
     */
    abstract public function attach();

    /**
     * @param       $class
     */
    protected function instanceResolver($class)
    {
        $this->resolve[$class] = self::$pointcuts;
    }

    /**
     * @return array
     */
    public function getResolver()
    {
        return $this->resolve;
    }
}
