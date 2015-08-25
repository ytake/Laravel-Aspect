# Laravel-Aspect
for laravel framework(use Go!Aop Framework)

[![Build Status](https://travis-ci.org/ytake/Laravel-Aspect.svg?branch=develop)](https://travis-ci.org/ytake/Laravel-Aspect)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/?branch=develop)
[![Coverage Status](https://coveralls.io/repos/ytake/Laravel-Aspect/badge.svg?branch=develop&service=github)](https://coveralls.io/github/ytake/Laravel-Aspect?branch=develop)

[![StyleCI](https://styleci.io/repos/40900709/shield)](https://styleci.io/repos/40900709)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3/mini.png)](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3)

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

