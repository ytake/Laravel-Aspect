<?php
/**
 * for test
 */

namespace __Test;

use Illuminate\Database\QueryException;
use Ytake\LaravelAspect\Annotation\Transactional;
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

    /**
     * @Transactional(value="testing")
     *
     * @return string
     */
    public function error()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        throw new QueryException("SELECT date('now')", [], new \Exception);
    }

    /**
     * @Transactional(value="testing",expect=\LogicException::class)
     *
     * @return string
     */
    public function errorException()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        throw new \LogicException;
    }
}
