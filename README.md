# Laravel-Aspect
aspect-oriented programming Package for laravel framework

[![Build Status](http://img.shields.io/travis/ytake/Laravel-Aspect/master.svg?style=flat-square)](https://travis-ci.org/ytake/Laravel-Aspect)
[![Coverage Status](http://img.shields.io/coveralls/ytake/Laravel-Aspect/master.svg?style=flat-square)](https://coveralls.io/r/ytake/Laravel-Aspect?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/55dc10228d9c4b001b000870/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55dc10228d9c4b001b000870)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/ytake/Laravel-Aspect.svg?style=flat-square)](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/?branch=master)

[![StyleCI](https://styleci.io/repos/40900709/shield)](https://styleci.io/repos/40900709)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3/mini.png)](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3)

[![License](http://img.shields.io/packagist/l/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)
[![Latest Version](http://img.shields.io/packagist/v/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)
[![Total Downloads](http://img.shields.io/packagist/dt/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)

This library is heavily inspired by the [jcabi/jcabi-aspects](https://github.com/jcabi/jcabi-aspects).

## usage 

### install 

```bash
$ composer require ytake/laravel-aspect
```
 or
 
 ```json
   "require": {
    "php": ">=5.5.9",
    "laravel/framework": "5.*",
    "ytake/laravel-aspect": "~1.0"
  },
 ```

### added serviceProvider

```php
'providers' => [
    // added AspectServiceProvider 
    \Ytake\LaravelAspect\AspectServiceProvider::class,
    // added Artisan Command
    \Ytake\LaravelAspect\ConsoleServiceProvider::class,
]
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
$ php artisan vendor:publish --provider="Ytake\LaravelAspect\AspectServiceProvider"
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

use Ytake\LaravelAspect\Modules\CacheableModule as PackageCacheableModule;

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

use Ytake\LaravelAspect\Annotation\Cacheable;

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
 
 
## Cache Clear Command

```bash
$ php artisan ytake:aspect-clear-cache
```

## Annotations

### @Transactional
for database transaction(illuminate/database)

you must use the TransactionalModule

* option

| params | description |
|-----|-------|
| value | database connection |
| expect | expect exception |

```php
use Ytake\LaravelAspect\Annotation\Transactional;

/**
 * @Transactional("master")
 */
public function save(array $params)
{
    return $this->eloquent->save($params)
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
use Ytake\LaravelAspect\Annotation\Cacheable;

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
use Ytake\LaravelAspect\Annotation\CacheEvict;

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
use Ytake\LaravelAspect\Annotation\CachePut;

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


```php
use Ytake\LaravelAspect\Annotation\Loggable;

class AspectLoggable
{
    /**
     * @Loggable
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
use Ytake\LaravelAspect\Annotation\LogExceptions;

class AspectLoggable
{
    /**
     * @LogExceptions
     * @param null $id
     * @return null
     */
    public function dispatchLogExceptions()
    {
        return $this->__toString();
    }
}

```

### @PostConstruct
The PostConstruct annotation is used on a method that needs to be executed after dependency injection is done to perform any initialization.

you must use the PostConstructModule

```php
use Ytake\LaravelAspect\Annotation\PostConstruct;

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
use Ytake\LaravelAspect\Annotation\RetryOnFailure;

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

### @Async
Methods annotated with @Async will return immediately to its caller while its operation executes asynchronously.

Methods annotated with @Async must strictly have a void

**required pcntl extension**
[PHP:PCNTL - Process Control](http://php.net/manual/en/book.pcntl.php)

```php
use Ytake\LaravelAspect\Annotation\Async;

class AspectAsync
{
    /**
     * @Async
     * @param null $id
     */
    public function asyncProcess()
    {
        sleep(10);
    }
}

```

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

## for testing
use none driver

```xml
<env name="ASPECT_DRIVER" value="none"/>
```
