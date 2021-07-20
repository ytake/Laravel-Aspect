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

namespace Bssd\LaravelAspect\Console;

use Illuminate\Console\Command;
use Bssd\LaravelAspect\AspectManager;

/**
 * Class ClearCacheCommand
 */
class CompileCommand extends Command
{
    /** @var string */
    protected $name = 'ytake:aspect-compile';

    /** @var string */
    protected $description = 'compiles all known classes';

    /** @var AspectManager */
    protected $aspectManager;

    /**
     * CompileCommand constructor.
     *
     * @param AspectManager $aspectManager
     */
    public function __construct(AspectManager $aspectManager)
    {
        parent::__construct();
        $this->aspectManager = $aspectManager;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->aspectManager->weave();
        $this->info('class files compiled!');
    }
}
