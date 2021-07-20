<?php
/**
 * for test
 */

namespace __Test;

use Bssd\LaravelAspect\Annotation\CachePut;

/**
 * Class AspectCachePut
 *
 * @package __Test
 * @author yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 */
class AspectCachePut
{
    /**
     * @CachePut(key="#id")
     * @param null $id
     * @return null
     */
    public function singleKey($id = null)
    {
        return $id;
    }

    /**
     * @CachePut(cacheName={"testing1"},tags="testing1")
     */
    public function throwExceptionCache()
    {
        return 'testing';
    }
}
