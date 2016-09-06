<?php

class AnnotationManagerTest extends \AspectTestCase
{
    /** @var \Ytake\LaravelAspect\AnnotationManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AnnotationManager($this->app);
    }

    public function testReturnDefaultDriverName()
    {
        $this->assertInternalType('string', $this->manager->getDefaultDriver());
    }

    public function testDefaultDriverInstance()
    {
        $this->assertInstanceOf(
            \Ytake\LaravelAspect\Annotation\Reader\ArrayReader::class,
            $this->manager->driver()
        );
    }

    public function testAnnotationDriverInstance()
    {
        $this->assertInstanceOf(
            \Ytake\LaravelAspect\Annotation\Reader\ArrayReader::class,
            $this->manager->driver('array')
        );
        $this->assertInstanceOf(
            \Ytake\LaravelAspect\Annotation\Reader\FileReader::class,
            $this->manager->driver('file')
        );
    }

    public function testAnnotationDriverImplementsAccessor()
    {
        $fileReader = $this->manager->driver('file');
        $this->assertInstanceOf(
            \Doctrine\Common\Annotations\CachedReader::class,
            $fileReader->getReader()
        );
        $arrayReader = $this->manager->driver('array');
        $this->assertInstanceOf(
            \Doctrine\Common\Annotations\AnnotationReader::class,
            $arrayReader->getReader()
        );
    }
}
