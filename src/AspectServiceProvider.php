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

namespace Ytake\LaravelAspect;

use Illuminate\Support\ServiceProvider;

/**
 * Class AspectServiceProvider
 */
class AspectServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = false;

    /**
     * boot aspect kernel
     */
    public function boot(): void
    {
        $this->app['aspect.manager']->weave();
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        /**
         * for package configure
         */
        $configPath = __DIR__.'/config/ytake-laravel-aop.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-aop');
        $this->publishes([$configPath => config_path('ytake-laravel-aop.php')], 'aspect');

        $this->app->singleton(AnnotationConfiguration::class, function ($app) {
            $annotationConfiguration = new AnnotationConfiguration(
                $app['config']->get('ytake-laravel-aop.annotation')
            );

            return $annotationConfiguration;
        });
        $this->app->singleton('aspect.manager', function ($app) {
            /** @var AnnotationConfiguration $annotationConfiguration */
            $annotationConfiguration = $app->make(AnnotationConfiguration::class);
            $annotationConfiguration->ignoredAnnotations();

            // register annotation
            return new AspectManager($app);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'aspect.manager',
        ];
    }
}
