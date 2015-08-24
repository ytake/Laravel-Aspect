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
use Ytake\LaravelAspect\Console\ClearCacheCommand;

/**
 * Class AspectServiceProvider
 *
 * @package Ytake\LaravelAspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class ConsoleServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = true;

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * @inheritdoc
     */
    public function register()
    {

    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.ytake.aspect.clear-cache', function ($app) {
            return new ClearCacheCommand($app['config'], $app['files']);
        });
        $this->commands(['command.ytake.aspect.clear-cache']);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return [
            'command.ytake.aspect.clear-cache'
        ];
    }
}
