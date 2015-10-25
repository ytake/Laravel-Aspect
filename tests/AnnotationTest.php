<?php

class AnnotationTest extends \TestCase
{
    /** @var \Ytake\LaravelAspect\Annotation */
    protected $annotation;

    public function setUp()
    {
        parent::setUp();
        $this->annotation = new \Ytake\LaravelAspect\Annotation;
    }

    public function testRegisterAnnotation()
    {
        $this->annotation->registerAnnotations([
            __DIR__ . '/AnnotationTest.php'
        ]);
        $this->annotation->registerAspectAnnotations();
        $reader = new \Doctrine\Common\Annotations\AnnotationReader();
        $annotation = $reader->getMethodAnnotation(
            new \ReflectionMethod(\Testable::class, 'testing'), 'Test'
        );
        $this->assertInstanceOf('Test', $annotation);
        $this->assertSame('test', $annotation->value);
    }

    /**
     * @expectedException \Ytake\LaravelAspect\Exception\FileNotFoundException
     */
    public function testNotFoundFileRegisterAnnotation()
    {
        $this->annotation->registerAnnotations([
            __DIR__ . 'TestingAnnotation.php'
        ]);
        $this->annotation->registerAspectAnnotations();
    }
}

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Test extends Annotation
{
    public $value = 'test';
}


class Testable
{
    /**
     * @\Test
     */
    public function testing()
    {

    }
}
