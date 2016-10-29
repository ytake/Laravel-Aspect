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
namespace Ytake\LaravelAspect\Modules;

use Ytake\LaravelAspect\PointCut\PointCutable;
use Illuminate\Contracts\Container\Container as Application;

/**
 * Class AspectModule
 */
abstract class AspectModule
{
    /** @var Application */
    protected $app;

    /** @var array */
    protected static $pointcuts = [];

    /** @var array */
    protected static $resolve = [];

    /** @var array */
    protected $classes = [];

    /**
     * AspectModule constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * attach pointcut
     *
     * @return void
     */
    public function attach()
    {
        // public attach
        if ($this->registerPointCut() instanceof PointCutable) {
            static::$pointcuts[] = $this->registerPointCut()->configure($this->app);
            foreach ($this->classes as $class) {
                $this->instanceResolver($class);
            }
        }
    }

    /**
     * @codeCoverageIgnore
     * @return PointCutable
     */
    protected function registerPointCut()
    {
        // register pointcut
    }

    /**
     * @param string $class
     */
    protected function instanceResolver($class)
    {
        static::$resolve[$class] = static::$pointcuts;
    }

    /**
     * @return array
     */
    public function getResolver()
    {
        return static::$resolve;
    }
}
