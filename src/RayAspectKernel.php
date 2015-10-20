<?php

namespace Ytake\LaravelAspect;

use Ray\Aop\Bind;
use Ray\Aop\Compiler;
use Ray\Aop\Matcher;
use PhpParser\Parser;
use PhpParser\Lexer;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use Illuminate\Contracts\Container\Container;
use Ytake\LaravelAspect\Modules\AspectModule;

/**
 * Class RayAspectKernel
 */
class RayAspectKernel implements AspectDriverInterface
{
    /** @var Container */
    protected $app;

    /** @var array */
    protected $configure;

    /** @var Compiler  */
    protected $compiler;

    /**
     * @param Container $app
     * @param array     $configure
     */
    public function __construct(Container $app, array $configure)
    {
        $this->app = $app;
        $this->configure = $configure;
        $this->compiler = $this->getCompiler();
    }

    /**
     * initialize aspect kernel
     *
     * @return void
     */
    public function register(AspectModule $module = null)
    {
        (new $module($this->app, new Bind()))->setCompiler($this->compiler)->add();
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
