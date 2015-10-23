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

use Illuminate\Support\ServiceProvider;
use Ytake\LaravelAspect\Console\ClearCacheCommand;
use Ytake\LaravelAspect\Console\ModulePublishCommand;
use Ytake\LaravelAspect\Console\ClearAnnotationCacheCommand;

/**
 * Class AspectServiceProvider
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = true;

    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // register bindings
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.ytake.aspect.clear-cache', function ($app) {
            return new ClearCacheCommand($app['config'], $app['files']);
        });
        $this->app->singleton('command.ytake.aspect.annotation.clear-cache', function ($app) {
            return new ClearAnnotationCacheCommand($app['config'], $app['files']);
        });
        $this->app->singleton('command.ytake.aspect.module-publish', function ($app) {
            return new ModulePublishCommand($app['files']);
        });
        $this->commands([
            'command.ytake.aspect.clear-cache',
            'command.ytake.aspect.annotation.clear-cache',
            'command.ytake.aspect.module-publish'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'command.ytake.aspect.clear-cache',
            'command.ytake.aspect.annotation.clear-cache',
            'command.ytake.aspect.module-publish'
        ];
    }
}
