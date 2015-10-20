<?php

namespace Ytake\LaravelAspect\Exception;

/**
 * Class FileNotFoundException
 */
class FileNotFoundException extends \Exception
{
    /**
     * @param string          $path
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($path, $code = 0, \Exception $previous = null)
    {
        parent::__construct('File not found at path: ' . $path, $code, $previous);
    }
}
