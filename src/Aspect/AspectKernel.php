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

namespace Ytake\LaravelAop\Aspect;

use Go\Core\AspectContainer;

/**
 * Class AspectKernel
 *
 * @package Ytake\LaravelAop\Aspect
 */
final class AspectKernel extends LaravelKernel
{
    /**
     * @inheritdoc
     */
    protected function configureAop(AspectContainer $container)
    {
        $container->registerAspect(new TransactionalAspect($this->laravel['db']));
        $container->registerAspect(new CacheableAspect($this->laravel['cache']));
        $container->registerAspect(new CacheEvictAspect($this->laravel['cache']));
    }
}
