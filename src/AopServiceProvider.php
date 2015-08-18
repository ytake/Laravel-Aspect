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
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class AopServiceProvider
 *
 * @package Ytake\LaravelAop
 */
class AopServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        $configPath = __DIR__ . '/config/ytake-laravel-aop.php';
        $this->mergeConfigFrom($configPath, 'ytake-laravel-aop');
        $this->publishes([$configPath => config_path('ytake-laravel-aop.php')], 'aspect');

        $this->app->singleton('aop.manager', function($app) {
            $this->registerAspectAnnotations();
            return new AspectManager($app);
        });
        $this->app->make('aop.manager')->register();
    }

    /**
     * use annotations
     *
     * @return void
     */
    protected function registerAspectAnnotations()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Transactional.php');
    }
}
