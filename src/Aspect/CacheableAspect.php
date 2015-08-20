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

use Go\Lang\Annotation\Around;
use Go\Aop\Intercept\MethodInvocation;

/**
 * Class CacheableAspect
 *
 * @package Ytake\LaravelAop\Aspect
 */
class CacheableAspect extends AbstractCache
{
    /**
     * @Around("@annotation(Cacheable)")
     * @param MethodInvocation $invocation
     * @return mixed
     */
    public function aroundMethodExecution(MethodInvocation $invocation)
    {
        /** @var \Cacheable $annotation */
        $annotation = $invocation->getMethod()->getAnnotation('Cacheable');

        $keys = $this->generateCacheName($annotation->cacheNames, $invocation);
        if (!is_array($annotation->key)) {
            $annotation->key = [$annotation->key];
        }

        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            if (in_array('#' . $parameter->getName(), $annotation->key)) {
                $keys[] = $arguments[$parameter->getPosition()];
            }
        }
        // detect use cache driver
        /** @var \Illuminate\Contracts\Cache\Repository $cache */
        $cache = $this->cache->store($annotation->driver);
        if ($result = $invocation->proceed()) {
            $cache->add(implode($this->join, $keys), $result, $annotation->lifetime);
        }

        return $result;
    }
}
