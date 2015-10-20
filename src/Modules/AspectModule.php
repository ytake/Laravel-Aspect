<?php

namespace Ytake\LaravelAspect\Modules;

use Ray\Aop\Bind;
use Ray\Aop\CompilerInterface;
use Illuminate\Contracts\Container\Container as Application;

/**
 * Class AspectModule
 */
abstract class AspectModule
{
    /** @var Application */
    protected $app;

    /** @var Bind */
    protected $bind;

    /** @var CompilerInterface */
    protected $compiler;

    /**
     * @param Application $app
     * @param Bind        $bind
     */
    public function __construct(Application $app, Bind $bind)
    {
        $this->app = $app;
        $this->bind = $bind;
    }

    /**
     * @return void
     */
    abstract public function add();

    /**
     * @param CompilerInterface $compiler
     *
     * @return $this
     */
    public function setCompiler(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
        return $this;
    }
}
