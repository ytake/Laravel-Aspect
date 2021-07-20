<?php
declare(strict_types=1);

namespace __Test;

use Bssd\LaravelAspect\Annotation\EagerQueue;
use Bssd\LaravelAspect\Annotation\LazyQueue;
use Bssd\LaravelAspect\Annotation\Loggable;
use Bssd\LaravelAspect\Annotation\MessageDriven;

/**
 * Class AspectMessageDriven
 */
class AspectMessageDriven
{
    /**
     * @Loggable
     * @MessageDriven(
     *     @LazyQueue(3),
     *     onQueue="message"
     * )
     * @param string $param
     * @return void
     */
    public function exec(string $param)
    {
        echo $param;
        $this->logWith($param);
    }

    /**
     * @MessageDriven(
     *     @EagerQueue
     * )
     * @param string $message
     */
    public function eagerExec(string $message)
    {
        $this->logWith($message);
    }

    /**
     * @Loggable(name="Queued")
     * @param string $message
     *
     * @return string
     */
    public function logWith(string $message)
    {
        return "Hello $message";
    }
}
