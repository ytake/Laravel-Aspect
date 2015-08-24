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

namespace Ytake\LaravelAspect;

use Illuminate\Support\ServiceProvider;

/**
 * Class AspectServiceProvider
 *
 * @package Ytake\LaravelAspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class CompileServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {

    }

    /**
     * @inheritdoc
     */
    public static function compiles()
    {
        return [
            base_path() . '/vendor/ytake/laravel-aspect/src/Annotation.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/AspectDriverInterface.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/AspectManager.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/AspectServiceProvider.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/ConsoleServiceProvider.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/GoAspect.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Annotation/Cacheable.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Annotation/CacheEvict.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Annotation/CachePut.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Annotation/Transactional.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Aspect/AspectKernel.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Aspect/CacheableAspect.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Aspect/CacheEvictAspect.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Aspect/CachePutAspect.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Aspect/LaravelAspect.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Aspect/TransactionalAspect.php',
            base_path() . '/vendor/ytake/laravel-aspect/src/Console/ClearCacheCommand.php',
        ];
    }
}
