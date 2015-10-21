<?php

namespace Ytake\LaravelAspect;

use Illuminate\Support\Manager;

/**
 * Class AspectManager
 * @method void register() register(string $module)
 */
class AspectManager extends Manager
{
    /**
     * for go-aop driver
     *
     * @return RayAspectKernel
     */
    protected function createRayDriver()
    {
        return new RayAspectKernel(
            $this->app,
            $this->getConfigure('ray')
        );
    }

    /**
     * @return NullAspectKernel
     */
    protected function createNoneDriver()
    {
        return new NullAspectKernel();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('ytake-laravel-aop.aspect.default');
    }

    /**
     * @param string $driver
     * @return string[]
     */
    protected function getConfigure($driver)
    {
        $aspectConfigure = $this->app['config']->get('ytake-laravel-aop.aspect.drivers');

        return $aspectConfigure[$driver];
    }
}
