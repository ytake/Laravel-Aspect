<?php

/**
 * for test
 */
namespace __Test;

use Bssd\LaravelAspect\Annotation\PostConstruct;

/**
 * Class FailedPostConstruct
 */
class FailedPostConstruct
{
    /** @var int  */
    protected $index;

    /**
     * FailedPostConstruct constructor.
     *
     * @param int $index
     */
    public function __construct($index = 1)
    {
        $this->index = $index;
    }

    /**
     * @PostConstruct
     */
    public function initialize()
    {
        $this->index += 1;
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
        return $this->index;
    }
}
