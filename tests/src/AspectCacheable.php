<?php
/**
 * for test
 */

namespace __Test;

use Ytake\LaravelAspect\Annotation\Cacheable;

/**
 * Class AspectCacheable
 *
 * @package __Test
 */
class AspectCacheable
{
    /**
     * @Cacheable(key="#id",driver="null")
     * @param null $id
     *
     * @return null
     */
    public function singleKey($id = null)
    {
        return $id;
    }

    /**
     * @Cacheable(key={"#id","#value"},driver="array")
     * @param $id
     * @param $value
     *
     * @return mixed
     */
    public function multipleKey($id, $value)
    {
        return $id;
    }

    /**
     * @Cacheable(cacheName="testing1",key={"#id","#value"})
     * @param $id
     * @param $value
     *
     * @return mixed
     */
    public function namedMultipleKey($id, $value)
    {
        return $id;
    }

    /**
     * @Cacheable(tags={"testing1","testing2"},key={"#id","#value"})
     * @param $id
     * @param $value
     *
     * @return mixed
     */
    public function namedMultipleNameAndKey($id, $value)
    {
        return $id;
    }

    /**
     * @Cacheable(tags={"testing1","testing2"},key={"#id","#class"})
     * @param           $id
     * @param \stdClass $class
     *
     * @return mixed
     */
    public function cachingKeyObject($id, \stdClass $class)
    {
        return $id;
    }

    /**
     * @Cacheable(negative=true,cacheName="negative")
     * @return null
     */
    public function negativeCache()
    {
        return null;
    }
}
