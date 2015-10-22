<?php

namespace Ytake\LaravelAspect\Modules;

use Ytake\LaravelAspect\PointCut\CacheEvictPointCut;

/**
 * Class CacheEvictModule
 */
class CacheEvictModule extends AspectModule
{
    /**
     * @var array
     */
    protected $classes = [
        // example
        // \App\Services\AcmeServce::class
    ];


    public function add()
    {
        $pointcut = $this->app->call([new CacheEvictPointCut, 'configure']);

        foreach ($this->classes as $class) {
            $bind = $this->bind->bind($class, [$pointcut]);
            $compiledClass = $this->compiler->compile($class, $bind);
            $this->app->bind($class, function ($app) use ($bind, $compiledClass) {
                $instance = $app->make($compiledClass);
                $instance->bindings = $bind->getBindings();
                return $instance;
            });
        }
    }
}