<?php

class TransactionalTest extends \TestCase
{

    /** @var \Ytake\LaravelAop\AspectManager $manager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $this->manager = new \Ytake\LaravelAop\AspectManager($this->app);
    }

    public function testTransactionalAssertString()
    {
        // $this->resolveManager();
        $transactional = new \__Test\AspectTransactionalString;
        $this->assertContains('testing', $transactional->start());
    }

    public function testTransactionalDatabase()
    {
        $this->resolveManager();
        $transactional = new \__Test\AspectTransactionalDatabase($this->app['db']);
        $this->assertInternalType('array', $transactional->start());
        $this->assertInstanceOf('stdClass', $transactional->start()[0]);
    }

    /**
     *
     */
    protected function resolveManager()
    {
        $annotation = new \Ytake\LaravelAop\Annotation;
        $annotation->registerAspectAnnotations();
        /** @var \Ytake\LaravelAop\GoAspect $aspect */
        $aspect = $this->manager->driver('go');
        $aspect->register();
    }
}
