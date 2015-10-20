<?php

namespace Ytake\LaravelAspect\Modules;

use Illuminate\Foundation\Application;
use Ytake\LaravelAspect\AspectRegisterable;
use Ytake\LaravelAspect\PointCut\TransactionalPointCut;

/**
 * Class CacheableModule
 */
class CacheableModule extends AspectRegisterable
{
    /**
     * @var array
     */
    protected $classes = [
        // example
        // \App\Services\AcmeServce::class
    ];

    /**
     * @param Application $app
     */
    public function add(Application $app)
    {
        $pointcut = $this->app->call([new TransactionalPointCut, 'configure']);

        foreach ($this->classes as $class) {
            $bind = (new Bind)->bind($class, $pointcut);
            $compiledClass = $this->getCompiler()->compile($class, $bind);
            $this->app->bind($class, function ($app) use ($bind, $compiledClass) {
                $instance = $app->make($compiledClass);
                $instance->bindings = $bind->getBindings();
                return $instance;
            });
        }
    }
}
