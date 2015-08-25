# Laravel-Aspect
for laravel framework(use Go!Aop Framework)

[![Build Status](http://img.shields.io/travis/ytake/Laravel-Aspect/master.svg?style=flat-square)](https://travis-ci.org/ytake/Laravel-Aspect)
[![Coverage Status](http://img.shields.io/coveralls/ytake/Laravel-Aspect/master.svg?style=flat-square)](https://coveralls.io/r/ytake/Laravel-Aspect?branch=master)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/ytake/Laravel-Aspect.svg?style=flat-square)](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/?branch=master)

[![StyleCI](https://styleci.io/repos/40900709/shield)](https://styleci.io/repos/40900709)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3/mini.png)](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3)

[![License](http://img.shields.io/packagist/l/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)
[![Latest Version](http://img.shields.io/packagist/v/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)
[![Total Downloads](http://img.shields.io/packagist/dt/ytake/laravel-aspect.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-aspect)

## usage

### install 

```bash
$ composer require ytake/laravel-aspect
```
 or
 
 ```json
   "require": {
    "php": ">=5.5.9",
    "laravel/framework": "5.1.*",
    "ytake/laravel-aspect": "~0.0"
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

### for optimize(production)
required config/compile.php

```php
'providers' => [
    \Ytake\LaravelAspect\CompileServiceProvider::class,
],
```

## Cache Clear Command

```bash
$ php artisan ytake:aspect-clear-cache
```

## Annotations

### @Transactional(Around Advice)
for database transaction(illuminate/database)

* option

| params | description |
|-----|-------|
| value | database connection |

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

### @Cacheable(After Advice)
for cache(illuminate/cache)

* option

| params | description |
|-----|-------|
| key | cache key |
| cacheName | cache name(merge cache key) |
| driver | Accessing Cache Driver(store) |
| lifetime | cache lifetime (default: 120min) |
| tags | Storing Tagged Cache Items |

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

### @CacheEvict(After Advice)
for cache(illuminate/cache) / remove cache

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

### @CachePut(After Advice)
for cache(illuminate/cache) / cache put

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

