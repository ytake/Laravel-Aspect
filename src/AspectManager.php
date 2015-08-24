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

use Illuminate\Support\Manager;

/**
 * Class AspectManager
 *
 * @package Ytake\LaravelAspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class AspectManager extends Manager
{
    /**
     * for go-aop driver
     *
     * @return GoAspect
     */
    protected function createGoDriver()
    {
        return new GoAspect(
            $this->app,
            $this->getConfigure('go')
        );
    }

    /**
     * @inheritdoc
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('ytake-laravel-aop.default');
    }

    /**
     * @param $driver
     * @return mixed
     */
    protected function getConfigure($driver)
    {
        $aspectConfigure = $this->app['config']->get('ytake-laravel-aop.aop');

        return $aspectConfigure[$driver];
    }
}
