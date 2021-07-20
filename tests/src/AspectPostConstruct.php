<?php

/**
 * for test
 */
namespace __Test;

use Bssd\LaravelAspect\Annotation\PostConstruct;

/**
 * Class AspectPostConstruct
 */
class AspectPostConstruct
{
    /** @var int  */
    protected $index;

    /**
     * AspectPostConstruct constructor.
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
     * @return int
     */
    public function getA()
    {
        return $this->index;
    }
}
