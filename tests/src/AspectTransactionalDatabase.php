<?php
/**
 * for test
 */

namespace __Test;

use Illuminate\Database\QueryException;
use Bssd\LaravelAspect\Annotation\Transactional;
use Illuminate\Database\ConnectionResolverInterface;

/**
 * Class AspectTransactionalDatabase
 *
 * @package __Test
 */
class AspectTransactionalDatabase
{
    /** @var ConnectionResolverInterface */
    protected $db;

    /**
     * @param ConnectionResolverInterface $db
     */
    public function __construct(ConnectionResolverInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @Transactional(value="testing")
     *
     * @return string
     */
    public function start()
    {
        return $this->db->connection()->select("SELECT date('now')");
    }

    /**
     * @Transactional(value="testing")
     */
    public function error()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        throw new QueryException("SELECT date('now')", [], new \Exception);
    }

    /**
     * @Transactional(value="testing",expect=\LogicException::class)
     */
    public function errorException()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        throw new \LogicException;
    }

    /**
     * @Transactional(value="testing")
     * @param array $record
     *
     * @return bool
     */
    public function appendRecord(array $record)
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');

        return $this->db->connection()->table("tests")->insert($record);
    }

    /**
     * @Transactional({"testing", "testing_second"})
     */
    public function multipleDatabaseAppendRecordException()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection('testing_second')->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        $this->db->connection('testing_second')->table("tests")->insert(['test' => 'testing second']);
        throw new QueryException("SELECT date('now')", [], new \Exception);
    }

    /**
     * @Transactional({"testing", "testing_second"})
     */
    public function multipleDatabaseAppendRecord()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection('testing_second')->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        $this->db->connection('testing_second')->table("tests")->insert(['test' => 'testing second']);

        return 'transaction test';
    }
}
