<?php

class TransactionalTest extends \TestCase
{

    /** @var \Ytake\LaravelAspect\AspectManager $manager */
    protected $manager;

    protected static $instance;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAspect\AspectManager($this->app);
        $this->resolveManager();
    }

    /**
     * @runInSeparateProcess
     */
    public function testTransactionalAssertString()
    {
        $transactional = new \__Test\AspectTransactionalString;
        $this->assertContains('testing', $transactional->start());
    }

    /**
     * @runInSeparateProcess
     */
    public function testTransactionalDatabase()
    {
        $transactional = new \__Test\AspectTransactionalDatabase($this->app['db']);
        $this->assertInternalType('array', $transactional->start());
        $this->assertInstanceOf('stdClass', $transactional->start()[0]);

    }

    /**
     *
     */
    protected function resolveManager()
    {
        $annotation = new \Ytake\LaravelAspect\Annotation;
        $annotation->registerAspectAnnotations();
        /** @var \Ytake\LaravelAspect\GoAspect $aspect */
        $aspect = $this->manager->driver('go');
        $aspect->register();
    }
}
