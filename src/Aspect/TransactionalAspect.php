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

use Go\Aop\Aspect;
use Go\Lang\Annotation\Around;
use Go\Aop\Intercept\MethodInvocation;
use Illuminate\Database\QueryException;
use Illuminate\Database\ConnectionResolverInterface;

/**
 * Class TransactionalAspect
 *
 * @package Ytake\LaravelAop\Aspect
 */
class TransactionalAspect implements Aspect
{
    /** @var ConnectionResolverInterface */
    protected $db;

    /**
     * @param ConnectionResolverInterface $db
     */
    public function __construct(ConnectionResolverInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @Around("@annotation(Transactional)")
     * @param MethodInvocation $invocation
     * @return mixed
     */
    public function aroundMethodExecution(MethodInvocation $invocation)
    {
        $connection = $invocation->getMethod()
            ->getAnnotation('Transactional')->value;
        $database = $this->db->connection($connection);
        $database->beginTransaction();
        try {
            $result = $invocation->proceed();
            $database->commit();
            return $result;
        } catch (QueryException $exception) {
            $database->rollBack();
            throw $exception;
        }

    }
}
