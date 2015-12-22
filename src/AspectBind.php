<?php

namespace Ytake\LaravelAspect;

use Ray\Aop\Bind;

/**
 * Class AspectBind
 */
class AspectBind
{
    /** @var bool */
    protected $cacheable;

    /** @var string */
    protected $path;

    /** @var string */
    protected $extension = '.cached.php';

    /**
     * CacheableBind constructor.
     * @param bool $cacheable
     * @param string $path
     */
    public function __construct($cacheable = false, $path)
    {
        $this->cacheable = $cacheable;
        $this->path = $path;
    }

    /**
     * @param $class
     * @param array $pointcuts
     * @return Bind
     */
    public function bind($class, array $pointcuts)
    {
        if (!$this->cacheable) {
            return (new Bind)->bind($class, $pointcuts);
        }
        $filePath = $this->path . "/{$class}" . $this->extension;
        if (!file_exists($filePath)) {
            $bind = (new Bind)->bind($class, $pointcuts);
            file_put_contents($filePath, serialize($bind));
        }
        return unserialize(file_get_contents($filePath));
    }
}
