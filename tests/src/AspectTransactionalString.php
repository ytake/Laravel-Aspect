<?php
/**
 * for test
 */

namespace __Test;

use Ytake\Lom\Meta\NoArgsConstructor;

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
