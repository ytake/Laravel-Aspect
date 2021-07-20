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

use Ray\Aop\MethodInvocation;
use Illuminate\Database\DatabaseManager;

/**
 * Class Execute
 */
final class Execute implements Runnable
{
    /** @var MethodInvocation */
    private $invocation;

    /**
     * Execute constructor.
     *
     * @param MethodInvocation $invocation
     */
    public function __construct(MethodInvocation $invocation)
    {
        $this->invocation = $invocation;
    }

    /**
     * @param  DatabaseManager  $databaseManager
     * @param  array            $expectedExceptions
     * @param  callable         $invoker
     *
     * @return object
     */
    public function __invoke(
        DatabaseManager $databaseManager,
        array $expectedExceptions,
        callable $invoker
    ) {
        return $this->invocation->proceed();
    }
}
