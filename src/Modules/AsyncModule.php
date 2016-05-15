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
 *
 * Copyright (c) 2015-2016 Yuuki Takezawa
 *
 */
namespace Ytake\LaravelAspect\Modules;

use Ytake\LaravelAspect\PointCut\PointCutable;
use Ytake\LaravelAspect\PointCut\AsyncPointCut;

/**
 * Class AsyncModule
 */
class AsyncModule extends AspectModule
{
    /** @var array */
    protected $classes = [];

    /**
     * @return PointCutable
     */
    protected function registerPointCut()
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('pcntl')) {
            throw new \LogicException("Asynchronous Execution requires pcntl extensions to be installed");
        }
        // @codeCoverageIgnoreEnd
        return new AsyncPointCut;
    }
}
