<?php
/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Ytake\LaravelAop;

use Illuminate\Support\ServiceProvider;

/**
 * Class AspectServiceProvider
 *
 * @package Ytake\LaravelAop
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class AspectServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        // register annotation
        $this->app->make('aspect.annotation.register')->registerAspectAnnotations();
        // annotation driver
        $this->app->make('aspect.manager')->register();
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        /**
         * for package configure
         */
        $configPath = __DIR__ . '/config/ytake-laravel-aop.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-aop');
        $this->publishes([$configPath => config_path('ytake-laravel-aop.php')], 'aspect');

        $this->registerAspectAnnotations();

        $this->app->singleton('aspect.manager', function ($app) {
            return new AspectManager($app);
        });
    }

    protected function registerAspectAnnotations()
    {
        $this->app->singleton('aspect.annotation.register', function () {
            return new Annotation();
        });
    }
}
