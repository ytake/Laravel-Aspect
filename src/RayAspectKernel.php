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
        $pointcutMarshal = [];
        foreach($this->configure['aspect'] as $aspect => $classes) {
            $aspectClass = 'Ytake\\LaravelAspect\\Execution\\' . $aspect . 'Execution';
            $execution = new $aspectClass;
            foreach($classes as $class) {
                $pointcutMarshal[$class][] = $execution->bootstrap($this->app);
            }
        }

        foreach($pointcutMarshal as $class => $pointcut) {
            $bind = (new Bind)->bind($class, $pointcut);
            $this->app->bind($class, function ($app) use ($bind, $class) {
                $class = $this->getCompiler()->compile($class, $bind);
                $instance = $app->make($class);
                $instance->bindings = $bind->getBindings();
                return $instance;
            });
        }
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
