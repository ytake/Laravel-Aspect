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

use Go\Lang\Annotation\After;
use Go\Aop\Intercept\MethodInvocation;

/**
 * Class CachePutAspect
 *
 * @package Ytake\LaravelAspect\Aspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class CachePutAspect extends AbstractCache
{
    /**
     * @After("@annotation(Ytake\LaravelAspect\Annotation\CachePut)")
     * @param MethodInvocation $invocation
     * @return mixed
     */
    public function afterMethodExecution(MethodInvocation $invocation)
    {
        $annotation = $invocation->getMethod()->getAnnotation('Ytake\LaravelAspect\Annotation\CachePut');

        $keys = $this->generateCacheName($annotation->cacheName, $invocation);
        if (!is_array($annotation->key)) {
            $annotation->key = [$annotation->key];
        }
        $keys = $this->detectCacheKeys($invocation, $annotation, $keys);
        // detect use cache driver
        $cache = $this->detectCacheRepository($annotation);

        if ($result = $invocation->proceed()) {
            $cache->put(implode($this->join, $keys), $result, $annotation->lifetime);
        }

        return $result;
    }
}
