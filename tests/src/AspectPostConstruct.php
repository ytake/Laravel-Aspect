<?php

/**
 * for test
 */
namespace __Test;

use Ytake\LaravelAspect\Annotation\PostConstruct;

/**
 * Class AspectPostConstruct
 */
class AspectPostConstruct
{
    /** @var int  */
    protected $a;

    /**
     * AspectPostConstruct constructor.
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
     * @return int
     */
    public function getA()
    {
        return $this->a;
    }
}
