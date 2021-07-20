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

namespace Ytake\LaravelAspect\Transaction;

use Exception;
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
     * @param  string           $exceptionName
     * @param  callable         $invoker
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(DatabaseManager $databaseManager, string $exceptionName, callable $invoker)
    {
        $database = $databaseManager->connection($this->connection);
        $database->beginTransaction();
        try {
            $result = $invoker($databaseManager, $exceptionName);
            $database->commit();
        } catch (Exception $exception) {
            // for default Exception
            if ($exception instanceof QueryException) {
                $database->rollBack();
                throw $exception;
            }
            if ($exception instanceof $exceptionName) {
                $database->rollBack();
                throw $exception;
            }
            $database->rollBack();
            throw $exception;
        }

        return $result;
    }
}
