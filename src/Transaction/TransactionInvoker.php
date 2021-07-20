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

namespace Bssd\LaravelAspect\Transaction;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;

/**
 * Class TransactionInvoker
 */
class TransactionInvoker implements Runnable
{
    /** @var string */
    protected $connection;

    /**
     * TransactionInvoker constructor.
     *
     * @param  string|null  $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param  DatabaseManager  $databaseManager
     * @param  array            $expectedExceptions
     * @param  callable         $invoker
     *
     * @return mixed
     * @throws \Throwable
     */
    public function __invoke(DatabaseManager $databaseManager, array $expectedExceptions, callable $invoker)
    {
        $database = $databaseManager->connection($this->connection);
        $database->beginTransaction();
        try {
            $result = $invoker($databaseManager, $expectedExceptions);
            $database->commit();
        } catch (\Exception $exception) {
            foreach ($expectedExceptions as $expected) {
                if ($exception instanceof $expected) {
                    $database->rollBack();
                    throw $exception;
                }
            }
            $database->rollBack();
            throw $exception;
        }

        return $result;
    }
}
