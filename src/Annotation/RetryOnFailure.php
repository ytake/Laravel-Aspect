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

namespace Ytake\LaravelAspect\Annotation;

/**
 * Class RetryOnFailure
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class RetryOnFailure
{
    /**
     * @param int $attempts How many times to retry.
     * @param int $delay Delay between attempts.
     * @param array $types When to retry (in case of what exception types).
     * @param string $ignore Exception types to ignore.
     */
    public function __construct(
        public int $attempts = 0,
        public int $delay = 0,
        public array $types = [
            \Exception::class,
        ],
        public string $ignore = \Exception::class
    ) {}
}
