<?php
/**
 * for test
 */

namespace __Test;

use Illuminate\Database\ConnectionResolverInterface;

/**
 * Class AspectTransactionalDatabase
 *
 * @package __Test
 */
class AspectTransactionalDatabase
{
    /** @var ConnectionResolverInterface  */
    protected $db;

    /**
     * @param ConnectionResolverInterface $db
     */
    public function __construct(ConnectionResolverInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @Transactional
     *
     * @return string
     */
    public function start()
    {
        return $this->db->connection()->select("SELECT date('now')");
    }
}
