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
}
