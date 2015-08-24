<?php
/**
 * for test
 */

namespace __Test;

/**
 * Class AspectCacheEvict
 *
 * @package __Test
 */
class AspectCacheEvict
{
    /**
     * @\CacheEvict
     * @return string
     */
    public function singleCacheDelete()
    {
        return 'testing';
    }

    /**
     * @\Cacheable(cacheName="testing",tags={"testing1"},key={"#id","#value"})
     * @param $id
     * @param $value
     * @return mixed
     */
    public function cached($id, $value)
    {
        return $id;
    }

    /**
     * @\CacheEvict(cacheName="testing",tags={"testing1"},allEntries=true)
     * @return null
     */
    public function removeCache()
    {
        return null;
    }
}
