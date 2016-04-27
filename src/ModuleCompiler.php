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
namespace Ytake\LaravelAspect;

use Illuminate\Contracts\Config\Repository;

/**
 * Class ModuleCompiler
 */
class ModuleCompiler
{
    const CONFIGURE_KEY = 'ytake-laravel-aop.aspect.module_compile.compile_dir';

    /** @var Repository */
    protected $repository;

    /**
     * ModuleCompiler constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getCompilerDir()
    {
        return $this->repository->get(self::CONFIGURE_KEY);
    }

    /**
     * @return mixed
     */
    public function getFileName($className)
    {
        return sprintf("%s%s", str_replace('\\', '', $className),
            sha1(crc32(filemtime((new \ReflectionClass($className))->getFileName())))
        );
    }

    /**
     * @param string $path
     * @param mixed $data
     * @return mixed
     */
    public function putCompiledFile($path, $data)
    {
        return file_put_contents($path, serialize($data));
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function unserializeCompiledFile($path)
    {
        return unserialize(file_get_contents($path));
    }
}
