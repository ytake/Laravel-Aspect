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
namespace Ytake\LaravelAspect;

use Illuminate\Support\Manager;

/**
 * Class AspectManager
 * @method void register(string $module)
 * @method void dispatch()
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
            $this->app['files'],
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
        $aspectConfigure[$driver]['modules'] = $this->app['config']->get('ytake-laravel-aop.aspect.modules', []);

        return $aspectConfigure[$driver];
    }
}
