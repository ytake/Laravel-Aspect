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

namespace Ytake\LaravelAspect;

use Ray\Aop\Bind;
use Illuminate\Support\Manager;

/**
 * Class AspectManager
 * @method void register() register(string $module)
 */
class AspectManager extends Manager
{
    /**
     * for go-aop driver
     *
     * @return RayAspectKernel
     */
    protected function createRayDriver()
    {
        return new RayAspectKernel(
            $this->app,
            new Bind(),
            $this->getConfigure('ray')
        );
    }

    /**
     * @return NullAspectKernel
     */
    protected function createNoneDriver()
    {
        return new NullAspectKernel();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('ytake-laravel-aop.aspect.default');
    }

    /**
     * @param string $driver
     * @return string[]
     */
    protected function getConfigure($driver)
    {
        $aspectConfigure = $this->app['config']->get('ytake-laravel-aop.aspect.drivers');

        return $aspectConfigure[$driver];
    }
}
