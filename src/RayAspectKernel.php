<?php

namespace Ytake\LaravelAspect;

use Ray\Aop\Compiler;
use Ray\Aop\Matcher;
use PhpParser\Parser;
use PhpParser\Lexer;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use Illuminate\Contracts\Container\Container;
use Ytake\LaravelAspect\Execution\CacheableExecution;
use Ytake\LaravelAspect\Execution\CacheEvictExecution;

/**
 * Class RayAspectKernel
 */
class RayAspectKernel implements AspectDriverInterface
{
    /** @var Container  */
    protected $app;

    /** @var array */
    protected $configure;

    /**
     * @param Container $app
     * @param array       $configure
     */
    public function __construct(Container $app, array $configure)
    {
        $this->app = $app;
        $this->configure = $configure;
    }

    /**
     * initialize aspect kernel
     *
     * @return void
     */
    public function register()
    {
        $compiler = $this->getCompiler();

        (new CacheableExecution())->bootstrap($this->app, $compiler);
        (new CacheEvictExecution())->bootstrap($this->app, $compiler);
    }

    /**
     * @param array $classes
     */
    public function setAspects(array $classes)
    {

    }

    /**
     * @return Compiler
     */
    protected function getCompiler()
    {
        return new Compiler($this->configure['cache_dir'], new CodeGen(
            new Parser(new Lexer()),
            new BuilderFactory(),
            new Standard()
        ));
    }
}
