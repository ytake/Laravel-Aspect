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

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Illuminate\Contracts\Cache\Factory as CacheFactory;

/**
 * Class AbstractCache
 *
 * @package Ytake\LaravelAspect\Aspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
abstract class AbstractCache implements Aspect
{
    /** @var string */
    protected $join = ":";

    /** @var CacheFactory */
    protected $cache;

    /**
     * @param CacheFactory $cache
     */
    public function __construct(CacheFactory $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param                  $name
     * @param MethodInvocation $invocation
     *
     * @return array
     */
    protected function generateCacheName($name, MethodInvocation $invocation)
    {
        if (is_array($name)) {
            throw new \InvalidArgumentException('Invalid argument');
        }
        if (is_null($name)) {
            $name = $invocation->getMethod()->name;
        }
        return [$name];
    }

    /**
     * @param MethodInvocation $invocation
     * @param                  $annotation
     * @param                  $keys
     *
     * @return array
     */
    protected function detectCacheKeys(MethodInvocation $invocation, $annotation, $keys)
    {
        $arguments = $invocation->getArguments();
        foreach ($invocation->getMethod()->getParameters() as $parameter) {
            // exclude object
            if (in_array('#' . $parameter->name, $annotation->key)) {
                if (!is_object($arguments[$parameter->getPosition()])) {
                    $keys[] = $arguments[$parameter->getPosition()];
                }
            }
        }

        return $keys;
    }

    /**
     * @param $annotation
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function detectCacheRepository($annotation)
    {
        /** @var \Illuminate\Contracts\Cache\Repository $cache */
        $cache = $this->cache->store($annotation->driver);
        if (count($annotation->tags)) {
            $cache = $cache->tags($annotation->tags);
            return $cache;
        }
        return $cache;
    }
}
