<?php

declare(strict_types=1);

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
 *
 * Copyright (c) 2015-2020 Yuuki Takezawa
 *
 */

namespace Bssd\LaravelAspect\Interceptor;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Bssd\LaravelAspect\Annotation\AnnotationReaderTrait;
use Bssd\LaravelAspect\Transaction\Execute;
use Bssd\LaravelAspect\Transaction\Runner;
use Bssd\LaravelAspect\Transaction\TransactionInvoker;

use function is_array;

/**
 * Class TransactionalInterceptor
 */
class TransactionalInterceptor implements MethodInterceptor
{
    use AnnotationReaderTrait;

    /** @var DatabaseManager */
    protected static $databaseManager;

    /**
     * @param  MethodInvocation  $invocation
     *
     * @return object
     * @throws \Exception
     */
    public function invoke(MethodInvocation $invocation)
    {
        $annotation = $invocation->getMethod()->getAnnotation($this->annotation) ?? new $this->annotation([]);
        // database connection name
        $connections = $annotation->value;
        if (!is_array($connections)) {
            $connections = [$connections];
        }
        $processes = [];
        foreach ($connections as $connection) {
            $processes[] = new TransactionInvoker($connection);
        }
        $processes[] = new Execute($invocation);
        $runner = new Runner($processes);
        return $runner(static::$databaseManager, $this->getExpectedExceptions($annotation));
    }


    private function getExpectedExceptions($annotation)
    {
        $annotation->expect = is_array($annotation->expect) ? $annotation->expect : [$annotation->expect];
        $result = [QueryException::class];
        foreach ($annotation->expect as $expected) {
            $result[] = ltrim($expected, '\\');
        }
        return $result;
    }

    /**
     * @param  DatabaseManager  $databaseManager
     */
    public function setDatabaseManager(DatabaseManager $databaseManager): void
    {
        static::$databaseManager = $databaseManager;
    }
}
