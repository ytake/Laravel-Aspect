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
    /** @var Container */
    protected $app;

    /** @var array */
    protected $configure;

    /**
     * @param Container $app
     * @param array     $configure
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
    public function register(AspectRegisterable $module = null)
    {
        $this->app->call([$module, 'add']);
        /*
        $pointcutMarshal = [];
        foreach ($this->configure['aspect'] as $aspect => $classes) {
            $aspectClass = 'Ytake\\LaravelAspect\\PointCut\\' . $aspect . 'PointCut';
            foreach ($classes as $class) {
                $pointcutMarshal[$class][] = $this->app->call([new $aspectClass, 'configure']);
            }
        }

        foreach ($pointcutMarshal as $class => $pointcut) {
            $bind = (new Bind)->bind($class, $pointcut);
            $compiledClass = $this->getCompiler()->compile($class, $bind);
            if ($compiledClass !== $class) {
                $this->app->bind($class, function ($app) use ($bind, $compiledClass) {
                    $instance = $app->make($compiledClass);
                    $instance->bindings = $bind->getBindings();
                    return $instance;
                });
            }
        }
        */
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
