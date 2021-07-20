# Laravel-Aspect

aspect-oriented programming Package for laravel framework

![Build Status](https://github.com/ytake/Laravel-Aspect/workflows/Tests/badge.svg?branch=master)
[![Coverage Status](http://img.shields.io/coveralls/ytake/Laravel-Aspect/master.svg?style=flat-square)](https://coveralls.io/r/ytake/Laravel-Aspect?branch=master)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/ytake/Laravel-Aspect.svg?style=flat-square)](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/?branch=master)

[![StyleCI](https://styleci.io/repos/40900709/shield)](https://styleci.io/repos/40900709)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3/mini.png)](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3)

[![License](http://img.shields.io/packagist/l/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)
[![Latest Version](http://img.shields.io/packagist/v/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)
[![Total Downloads](http://img.shields.io/packagist/dt/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)

This library is heavily inspired by the [jcabi/jcabi-aspects](https://github.com/jcabi/jcabi-aspects).

## usage

### Laravel version Compatibility

Laravel  | Package
:---------|:----------
5.0.x    | 1.x
5.1.x    | 1.x
5.2.x    | 1.x
5.3.x    | 1.x
5.4.x    | 1.x
5.5.x    | 2.0.*
5.6.x    | 2.1.*
5.7.x    | 3.0.*
6.0.x    | 4.0
7.x      | 6.0
8.x      | 7.0

### install

```bash
$ composer require ytake/laravel-aspect
```

*Supported Auto-Discovery(^Laravel5.5)*

#### for Laravel5.1, 5.2

[branch](https://github.com/ytake/Laravel-Aspect/tree/master-laravel5-legacy)

 ```json
   "require": {
"php": ">=5.5.9",
"laravel/framework": "5.*",
"ytake/laravel-aspect": "^1.0"
},
 ```

#### for Laravel5.6

[Laravel-Aspect Supported Laravel5.6](https://github.com/ytake/Laravel-Aspect/blob/master-laravel5.6)

 ```json
   "require": {
"php": ">=7.1.3",
"laravel/framework": "^5.7",
"ytake/laravel-aspect": "^3.0.0"
},
 ```

### added serviceProvider

```php
'providers' => [
    // added AspectServiceProvider 
    \Bssd\LaravelAspect\AspectServiceProvider::class,
    // added Artisan Command
    \Bssd\LaravelAspect\ConsoleServiceProvider::class,
]
```

### for Lumen

Add App\Providers\LumenAspectServiceProvider to your bootstrap/app.php file.

```php
$app->register(\App\Providers\LumenAspectServiceProvider::class);
$app->register(\Bssd\LaravelAspect\ConsoleServiceProvider::class);
```

### publish aspect module class

```bash
$ php artisan ytake:aspect-module-publish
```

more command options [--help]

### publish configure

* basic

```bash
$ php artisan vendor:publish
```

* use tag option

```bash
$ php artisan vendor:publish --tag=aspect
```

* use provider

```bash
$ php artisan vendor:publish --provider="Bssd\LaravelAspect\AspectServiceProvider"
```

### register aspect module

config/ytake-laravel-aop.php

```php
        'modules' => [
            // append modules
            // \App\Modules\CacheableModule::class,
        ],
```

use classes property

```php
namespace App\Modules;

use Bssd\LaravelAspect\Modules\CacheableModule as PackageCacheableModule;

/**
 * Class CacheableModule
 */
class CacheableModule extends PackageCacheableModule
{
    /** @var array */
    protected $classes = [
        \YourApplication\Services\SampleService::class
    ];
}
```

example

```php

namespace YourApplication\Services;

use Bssd\LaravelAspect\Annotation\Cacheable;

class SampleService
{
    /**
     * @Cacheable(cacheName="testing1",key={"#id"})
     */
    public function action($id) 
    {
        return $this;
    }
}
```

*notice*

- Must use a service container
- Classes must be non-final
- Methods must be public

### for Lumen

override `Bssd\LaravelAspect\AspectServiceProvider`

```php
use Bssd\LaravelAspect\AspectManager;
use Bssd\LaravelAspect\AnnotationManager;
use Bssd\LaravelAspect\AspectServiceProvider as AspectProvider;

/**
 * Class AspectServiceProvider
 */
final class AspectServiceProvider extends AspectProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->configure('ytake-laravel-aop');
        $this->app->singleton('aspect.manager', function ($app) {
            $annotationConfiguration = new AnnotationConfiguration(
                $app['config']->get('ytake-laravel-aop.annotation')
            );
            $annotationConfiguration->ignoredAnnotations();
            // register annotation
            return new AspectManager($app);
        });
    }
}

```

bootstrap/app.php

```php
$app->register(App\Providers\AspectServiceProvider::class);

if ($app->runningInConsole()) {
    $app->register(Bssd\LaravelAspect\ConsoleServiceProvider::class);
}
```

## Cache Clear Command

```bash
$ php artisan ytake:aspect-clear-cache
```

## PreCompile Command

```bash
$ php artisan ytake:aspect-compile
```

## Annotations

### @Transactional

for database transaction(illuminate/database)

you must use the TransactionalModule

* option

| params | description |
|-----|-------|
| value (or array) | database connection |
| expect | expect exception |

```php
use Bssd\LaravelAspect\Annotation\Transactional;

/**
 * @Transactional("master")
 */
public function save(array $params)
{
    return $this->eloquent->save($params);
}
```

#### Multiple Transaction

```php
use Bssd\LaravelAspect\Annotation\Transactional;

/**
 * @Transactional({"master", "second_master"})
 */
public function save(array $params)
{
    $this->eloquent->save($params);
    $this->query->save($params);
}
```

#### Exception Rollback

```php
use Bssd\LaravelAspect\Annotation\Transactional;

/**
 * @Transactional(expect="\QueryException")
 */
public function save(array $params)
{
    $this->eloquent->save($params);
    $this->query->save($params);
}
```

#### Multiple Exception Rollback
```php
use Bssd\LaravelAspect\Annotation\Transactional;

/**
 * @Transactional(expect={"\QueryException", "\RuntimeException"})
 */
public function save(array $params)
{
    $this->eloquent->save($params);
    $this->query->save($params);
}
```

### @Cacheable

for cache(illuminate/cache)

you must use the CacheableModule

* option

| params | description |
|-----|-------|
| key | cache key |
| cacheName | cache name(merge cache key) |
| driver | Accessing Cache Driver(store) |
| lifetime | cache lifetime (default: 120min) |
| tags | Storing Tagged Cache Items |
| negative(bool) | for null value (default: false) |

```php
use Bssd\LaravelAspect\Annotation\Cacheable;

/**
 * @Cacheable(cacheName="testing1",key={"#id","#value"})
 * @param $id
 * @param $value
 * @return mixed
 */
public function namedMultipleKey($id, $value)
{
    return $id;
}
```

### @CacheEvict

for cache(illuminate/cache) / remove cache

you must use the CacheEvictModule

* option

| params | description |
|-----|-------|
| key | cache key |
| cacheName | cache name(merge cache key) |
| driver | Accessing Cache Driver(store) |
| tags | Storing Tagged Cache Items |
| allEntries | flush(default:false) |

```php
use Bssd\LaravelAspect\Annotation\CacheEvict;

/**
 * @CacheEvict(cacheName="testing",tags={"testing1"},allEntries=true)
 * @return null
 */
public function removeCache()
{
    return null;
}
```

### @CachePut

for cache(illuminate/cache) / cache put

you must use the CachePutModule

* option

| params | description |
|-----|-------|
| key | cache key |
| cacheName | cache name(merge cache key) |
| driver | Accessing Cache Driver(store) |
| lifetime | cache lifetime (default: 120min) |
| tags | Storing Tagged Cache Items |

```php
use Bssd\LaravelAspect\Annotation\CachePut;

/**
 * @CachePut(cacheName={"testing1"},tags="testing1")
 */
public function throwExceptionCache()
{
    return 'testing';
}
```

### @Loggable / @LogExceptions

for logger(illuminate/log, monolog)

you must use the LoggableModule / LogExceptionsModule

* option

| params | description |
|-----|-------|
| value | log level (default: \Monolog\Logger::INFO) should Monolog Constants |
| skipResult | method result output to log |
| name |log name prefix(default: Loggable) |
| driver | logger driver or channel name [docs](https://laravel.com/docs/5.6/logging#configuration) |

```php
use Bssd\LaravelAspect\Annotation\Loggable;

class AspectLoggable
{
    /**
     * @Loggable(driver="stack")
     * @param null $id
     * @return null
     */
    public function normalLog($id = null)
    {
        return $id;
    }
}

```

sample)

```
[2015-12-23 08:15:30] testing.INFO: Loggable:__Test\AspectLoggable.normalLog {"args":{"id":1},"result":1,"time":0.000259876251221}
```

#### About @LogExceptions

**Also, take a look at @Loggable. This annotation does the same, but also logs non-exceptional situations.**

```php
use Bssd\LaravelAspect\Annotation\LogExceptions;

class AspectLoggable
{
    /**
     * @LogExceptions(driver="custom")
     * @param null $id
     * @return null
     */
    public function dispatchLogExceptions()
    {
        return $this->__toString();
    }
}

```

#### About @QueryLog

for database query logger(illuminate/log, monolog, illuminate/database)

```php

use Bssd\LaravelAspect\Annotation\QueryLog;
use Illuminate\Database\ConnectionResolverInterface;

/**
 * Class AspectQueryLog
 */
class AspectQueryLog
{
    /** @var ConnectionResolverInterface */
    protected $db;

    /**
     * @param ConnectionResolverInterface $db
     */
    public function __construct(ConnectionResolverInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @QueryLog(driver="custom")
     */
    public function multipleDatabaseAppendRecord()
    {
        $this->db->connection()->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection('testing_second')->statement('CREATE TABLE tests (test varchar(255) NOT NULL)');
        $this->db->connection()->table("tests")->insert(['test' => 'testing']);
        $this->db->connection('testing_second')->table("tests")->insert(['test' => 'testing second']);
    }
}

```

```
testing.INFO: QueryLog:AspectQueryLog.multipleDatabaseAppendRecord {"queries":[{"query":"CREATE TABLE tests (test varchar(255) NOT NULL)","bindings":[],"time":0.58,"connectionName":"testing"},{"query":"CREATE TABLE tests (test varchar(255) NOT NULL)","bindings":[],"time":0.31,"connectionName":"testing_second"} ...
```

### @PostConstruct

The PostConstruct annotation is used on a method that needs to be executed after dependency injection is done to perform
any initialization.

you must use the PostConstructModule

```php
use Bssd\LaravelAspect\Annotation\PostConstruct;

class Something
{
    protected $abstract;
    
    protected $counter = 0;
    
    public function __construct(ExampleInterface $abstract)
    {
        $this->abstract = $abstract;
    }
    
    /**
     * @PostConstruct
     */
    public function init()
    {
        $this->counter += 1;
    }
    
    /**
     * @return int
     */
    public function returning()
    {
        return $this->counter;
    }
}

```

**The method MUST NOT have any parameters**

### @RetryOnFailure

Retry the method in case of exception.

you must use the RetryOnFailureModule.

* option

| params | description |
|-----|-------|
| attempts (int) | How many times to retry. (default: 0) |
| delay (int) | Delay between attempts. (default: 0 / sleep(0) ) |
| types (array) | When to retry (in case of what exception types). (default: <\Exception::class> ) |
| ignore (string) | Exception types to ignore. (default: \Exception ) |

```php
use Bssd\LaravelAspect\Annotation\RetryOnFailure;

class ExampleRetryOnFailure
{
    /** @var int */
    public $counter = 0;

    /**
     * @RetryOnFailure(
     *     types={
     *         LogicException::class,
     *     },
     *     attempts=3,
     *     ignore=Exception::class
     * )
     */
    public function ignoreException()
    {
        $this->counter += 1;
        throw new \Exception;
    }
}

```

### @MessageDriven

Annotation for a Message Queue(illuminate/queue. illuminate/bus).

you must use the MessageDrivenModule.

* option

| params | description |
|-----|-------|
| value (Delayed) | \Bssd\LaravelAspect\Annotation\LazyQueue or \Bssd\LaravelAspect\Annotation\EagerQueue (default: EagerQueue)|
| onQueue (string) | To specify the queue. (default: null) ) |
| mappedName (string) | queue connection. (default: null/ default queue driver) |

```php
use Bssd\LaravelAspect\Annotation\EagerQueue;
use Bssd\LaravelAspect\Annotation\LazyQueue;
use Bssd\LaravelAspect\Annotation\Loggable;
use Bssd\LaravelAspect\Annotation\MessageDriven;

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
        echo $param;
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

```

#### LazyQueue

Handle Class *Bssd\LaravelAspect\Queue\LazyMessage*

#### EagerQueue

Handle Class *Bssd\LaravelAspect\Queue\EagerMessage*

### Ignore Annotations

use config/ytake-laravel-aspect.php file

default: LaravelCollective/annotations

```php
    'annotation' => [
        'ignores' => [
            // global Ignored Annotations
            'Hears',
            'Get',
            'Post',
            'Put',
            'Patch',
            'Options',
            'Delete',
            'Any',
            'Middleware',
            'Resource',
            'Controller'
        ],
    ],
```

### Append Custom Annotations

```php
    'annotation' => [
        'ignores' => [
            // global Ignored Annotations
            'Hears',
            'Get',
            'Post',
            'Put',
            'Patch',
            'Options',
            'Delete',
            'Any',
            'Middleware',
            'Resource',
            'Controller'
        ],
        'custom' => [
            \Acme\Annotations\Transactional::class
            // etc...
        ]
    ],
```

## for testing

use none driver

```xml

<env name="ASPECT_DRIVER" value="none"/>
```
