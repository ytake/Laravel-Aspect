# Laravel.Aop
for laravel framework 
(develop)

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
 
|-----|-------|
| connection | database connection |

```php
/**
 * @Transactional("master")
 */
public function save(array $params)
{
    return $this->eloquent->save($params)
}

```
