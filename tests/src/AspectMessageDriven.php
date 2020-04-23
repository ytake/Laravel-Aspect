<?php
declare(strict_types=1);

namespace __Test;

use Ytake\LaravelAspect\Annotation\EagerQueue;
use Ytake\LaravelAspect\Annotation\LazyQueue;
use Ytake\LaravelAspect\Annotation\Loggable;
use Ytake\LaravelAspect\Annotation\MessageDriven;

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
