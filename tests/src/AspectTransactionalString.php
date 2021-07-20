<?php
/**
 * for test
 */

namespace __Test;

use Bssd\LaravelAspect\Annotation\Transactional;

/**
 * Class AspectTransactional
 * @package Test
 */
class AspectTransactionalString
{
    /**
     * @Transactional(value="testing")
     *
     * @return string
     */
    public function start()
    {
        return 'testing';
    }
}
