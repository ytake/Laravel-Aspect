<?php

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
     * @return void
     */
    public function exec($param)
    {
        $this->logWith($param);
    }

    /**
     * @MessageDriven(
     *     @EagerQueue
     * )
     * @param string $message
     */
    public function eagerExec($message)
    {
        $this->logWith($message);
    }

    /**
     * @Loggable(name="Queued")
     * @param string $message
     *
     * @return string
     */
    public function logWith($message)
    {
        return "Hello $message";
    }
}
