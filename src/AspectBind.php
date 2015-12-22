<?php

namespace Ytake\LaravelAspect;

use Ray\Aop\Bind;
use Illuminate\Filesystem\Filesystem;

/**
 * Class AspectBind
 */
class AspectBind
{
    /** @var bool */
    protected $cacheable;

    /** @var string */
    protected $path;

    /** @var Filesystem  */
    protected $filesystem;

    /** @var string */
    protected $extension = '.cached.php';

    /**
     * AspectBind constructor.
     * @param Filesystem $filesystem
     * @param bool $cacheable
     * @param $path
     */
    public function __construct(Filesystem $filesystem, $cacheable = false, $path)
    {
        $this->filesystem = $filesystem;
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
        if (!$this->filesystem->exists($filePath)) {
            $bind = (new Bind)->bind($class, $pointcuts);
            $this->filesystem->put($filePath, serialize($bind));
        }
        return unserialize(file_get_contents($filePath));
    }
}
