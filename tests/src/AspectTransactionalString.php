<?php
/**
 * for test
 */

namespace __Test;

use Ytake\Lom\Meta\NoArgsConstructor;
use Ytake\LaravelAspect\Annotation\Transactional;

/**
 * Class AspectTransactional
 * @NoArgsConstructor
 * @package Test
 */
class AspectTransactionalString
{
    /**
     * @Transactional
     *
     * @return string
     */
    public function start()
    {
        return 'testing';
    }
}
