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

namespace Ytake\LaravelAspect\Aspect;

use Go\Core\AspectContainer;

/**
 * Class AspectKernel
 *
 * @package Ytake\LaravelAspect\Aspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
final class AspectKernel extends LaravelKernel
{
    /** @var array */
    protected $aspects;

    /**
     * @param array $classes
     */
    public function setAop(array $classes)
    {
        $this->aspects = $classes;
    }

    /**
     * @inheritdoc
     */
    protected function configureAop(AspectContainer $container)
    {
        if ($this->aspects) {
            foreach ($this->aspects as $aspect) {
                $container->registerAspect($this->laravel->make($aspect));
            }
        }
        $container->registerAspect(new TransactionalAspect($this->laravel['db']));
        $container->registerAspect(new CacheableAspect($this->laravel['cache']));
        $container->registerAspect(new CacheEvictAspect($this->laravel['cache']));
        $container->registerAspect(new CachePutAspect($this->laravel['cache']));
    }
}
