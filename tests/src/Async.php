<?php
/**
 * for test
 */

namespace __Test;

/**
 * Class AspectCacheable
 *
 * @package __Test
 */
class Async
{
    /**
     * @\Ytake\LaravelAspect\Annotation\Async
     * @return int
     */
    public function save()
    {
        sleep(10);
        return 1;
    }
}
