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

use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class Annotation
 *
 * @package Ytake\LaravelAspect
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
class Annotation
{
    /** @var string[] */
    protected $files = [];

    /**
     * @param array $files
     */
    public function registerAnnotations(array $files)
    {
        $this->files = $files;
    }

    /**
     * use annotations
     *
     * @return void
     */
    public function registerAspectAnnotations()
    {
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                AnnotationRegistry::registerFile($file);
            }
        }
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Transactional.php');
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Cacheable.php');
        AnnotationRegistry::registerFile(__DIR__ . '/Annotation/CacheEvict.php');
    }
}
