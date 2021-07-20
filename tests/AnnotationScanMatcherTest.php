<?php

use Bssd\LaravelAspect\Annotation\PostConstruct;
use Bssd\LaravelAspect\Matcher\AnnotationScanMatcher;

/**
 * Class AnnotationScanMatcherTest
 */
class AnnotationScanMatcherTest extends \AspectTestCase
{
    /** @var AnnotationScanMatcher */
    private $matcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->matcher = new AnnotationScanMatcher;
    }

    public function testShouldBeBoolean()
    {
        $result = $this->matcher->matchesClass(new \ReflectionClass(\__Test\AspectPostConstruct::class), [
            PostConstruct::class
        ]);
        $this->assertTrue($result);
        $result = $this->matcher->matchesClass(new \ReflectionClass(\__Test\FailedPostConstruct::class), [
            PostConstruct::class
        ]);
        $this->assertFalse($result);

        $reflectionClass = new \ReflectionClass(\__Test\AspectPostConstruct::class);
        $result = $this->matcher->matchesMethod($reflectionClass->getMethod('getA'), [
            PostConstruct::class
        ]);
        $this->assertTrue($result);
        $reflectionClass = new \ReflectionClass(\__Test\FailedPostConstruct::class);
        $result = $this->matcher->matchesMethod($reflectionClass->getMethod('getA'), [
            PostConstruct::class
        ]);
        $this->assertFalse($result);
    }
}
