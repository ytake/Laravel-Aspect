<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 * Copyright (c) 2015 Yuuki Takezawa
 *
 *
 * CodeGenMethod Class, CodeGen Class is:
 * Copyright (c) 2012-2015, The Ray Project for PHP
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

namespace Ytake\LaravelAspect\Interceptor;

use Ray\Aop\MethodInvocation;
use Ray\Aop\MethodInterceptor;
use Illuminate\Database\QueryException;
use Ytake\LaravelAspect\Annotation\AnnotationReaderTrait;

/**
 * Class TransactionalInterceptor
 */
class TransactionalInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /**
     * @param MethodInvocation $invocation
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        $annotation = $this->reader
            ->getMethodAnnotation($invocation->getMethod(), $this->annotation);

        $connection = $annotation->value;
        /** @var  $database */
        $database = app('db')->connection($connection);
        $database->beginTransaction();
        try {
            $result = $invocation->proceed();
            $database->commit();

            return $result;
        } catch (\Exception $exception) {
            // for default Exception
            if($exception instanceof QueryException) {
                $database->rollBack();
                throw $exception;
            }
            if($exception instanceof $annotation->expect) {
                $database->rollBack();
                throw $exception;
            }
            throw $exception;
        }
    }
}
