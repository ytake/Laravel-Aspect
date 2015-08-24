# Laravel-Aspect
for laravel framework(develop)

[![Build Status](https://travis-ci.org/ytake/Laravel-Aspect.svg?branch=develop)](https://travis-ci.org/ytake/Laravel-Aspect)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/ytake/Laravel-Aspect/?branch=develop)
[![Coverage Status](https://coveralls.io/repos/ytake/Laravel-Aspect/badge.svg?branch=develop&service=github)](https://coveralls.io/github/ytake/Laravel-Aspect?branch=develop)

[![StyleCI](https://styleci.io/repos/40900709/shield)](https://styleci.io/repos/40900709)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3/mini.png)](https://insight.sensiolabs.com/projects/70dace68-fe04-4039-aeb4-47a64c6acca3)

## usage

```php
'providers' => [
    // added 
    \Ytake\LaravelAop\AopServiceProvider
]
```

## Annotations

### @Transactional
for database transaction

* option

| params | description |
|-----|-------|
| value | database connection |

```php
/**
 * @Transactional("master")
 */
public function save(array $params)
{
    return $this->eloquent->save($params)
}

```

### @Cacheable


### @CacheEvict
