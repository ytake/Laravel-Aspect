<?php

/**
 * for test
 */
namespace __Test;

use Ytake\LaravelAspect\Annotation\PostConstruct;

/**
 * Class FailedPostConstruct
 */
class FailedPostConstruct
{
    /** @var int  */
    protected $a;

    /**
     * FailedPostConstruct constructor.
     *
     * @param int $a
     */
    public function __construct($a = 1)
    {
        $this->a = $a;
    }

    /**
     * @PostConstruct
     */
    public function initialize()
    {
        $this->a += 1;
    }

    /**
     * @PostConstruct
     */
    public function initializeTwo()
    {

    }

    /**
     * @return int
     */
    public function getA()
    {
        return $this->a;
    }
}
