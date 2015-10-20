<?php

namespace Ytake\LaravelAspect;

use Illuminate\Support\Manager;

/**
 * Class AspectManager
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
        return $this->app['config']->get('ytake-laravel-aop.default');
    }

    /**
     * @param string $driver
     * @return string[]
     */
    protected function getConfigure($driver)
    {
        $aspectConfigure = $this->app['config']->get('ytake-laravel-aop.aop');

        return $aspectConfigure[$driver];
    }
}
