<?php
/**
 * for test
 */

namespace __Test;

use Bssd\LaravelAspect\Annotation\Cacheable;
use Bssd\LaravelAspect\Annotation\CacheEvict;

/**
 * Class AspectCacheEvict
 *
 * @package __Test
 */
class AspectCacheEvict
{
    /**
     * @CacheEvict(cacheName="singleCacheDelete")
     * @return string
     */
    public function singleCacheDelete()
    {
        return 'testing';
    }

    /**
     * @Cacheable(cacheName="testing",tags={"testing1"},key={"#id","#value"})
     * @param $id
     * @param $value
     * @return mixed
     */
    public function cached($id, $value)
    {
        return $id;
    }

    /**
     * @CacheEvict(cacheName="testing",tags={"testing1"},allEntries=true)
     * @return null
     */
    public function removeCache()
    {
        return null;
    }
}
