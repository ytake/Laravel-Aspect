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

use Go\Lang\Annotation\After;
use Go\Aop\Intercept\MethodInvocation;

/**
 * Class CacheEvictAspect
 *
 * @package Ytake\LaravelAop\Aspect
 */
class CacheEvictAspect extends AbstractCache
{
    /**
     * @After("@annotation(CacheEvict)")
     * @param MethodInvocation $invocation
     * @return mixed
     */
    public function afterMethodExecution(MethodInvocation $invocation)
    {
        /** @var \CacheEvict $annotation */
        $annotation = $invocation->getMethod()->getAnnotation('CacheEvict');

        $keys = $this->generateCacheName($annotation->cacheNames, $invocation);
        if (!is_array($annotation->key)) {
            $annotation->key = [$annotation->key];
        }

        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            if (in_array('#' . $parameter->name, $annotation->key)) {
                $keys[] = $arguments[$parameter->getPosition()];
            }
        }
        // detect use cache driver
        /** @var \Illuminate\Contracts\Cache\Repository $cache */
        $cache = $this->cache->store($annotation->driver);
        $cache->forget(implode($this->join, $keys));
    }
}
