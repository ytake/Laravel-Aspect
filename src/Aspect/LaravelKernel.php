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

use Go\Core\AspectKernel as CoreKernel;
use Illuminate\Contracts\Container\Container as LaravelApplication;

/**
 * Class LaravelKernel
 *
 * @package Ytake\LaravelAop\Aspect
 */
abstract class LaravelKernel extends CoreKernel
{
    /** @var LaravelApplication */
    protected $laravel;

    /**
     * @param LaravelApplication $laravel
     */
    public function setLaravel(LaravelApplication $laravel)
    {
        $this->laravel = $laravel;
    }
}
