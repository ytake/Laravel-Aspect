<?php

/**
 * Class ResolveMockClass
 *
 * for testing
 */
class ResolveMockClass implements ResolveMockInterface
{
    /**
     * @\Bssd\LaravelAspect\Annotation\Cacheable(
     *     cacheName="testing.resolve.instance",
     *     driver="array"
     * )
     * @return int
     */
    public function get()
    {
        return time();
    }
}
