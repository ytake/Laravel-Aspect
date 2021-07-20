<?php
/**
 * for test
 */

namespace __Test;

use Bssd\LaravelAspect\Annotation\QueryLog;
use Bssd\LaravelAspect\Annotation\Transactional;
use Illuminate\Database\ConnectionResolverInterface;

/**
 * Class AspectQueryLog
 */
class AspectQueryLog
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
     * @QueryLog(driver="stack")
     * @Transactional(value="testing")
     *
     * @return string
     */
    public function start()
    {
        return $this->db->connection()->select("SELECT date('now')");
    }

    /**
     * @QueryLog(driver="stack")
     * @Transactional(value="testing")
     * @param array $record
     *
     * @return bool
     */
    public function appendRecord(array $record)
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');

        $this->db->connection()->table("tests")->insert($record);
        throw new \Exception;
    }

    /**
     * @Transactional({"testing", "testing_second"})
     * @QueryLog(driver="stack")
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
