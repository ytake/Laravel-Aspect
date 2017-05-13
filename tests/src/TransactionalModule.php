<?php

namespace __Test;

use Ytake\LaravelAspect\Modules\TransactionalModule as Transactional;

class TransactionalModule extends Transactional
{
    /**
     * @var array
     */
    protected $classes = [
        AspectTransactionalDatabase::class,
        AspectTransactionalString::class,
        AspectQueryLog::class
    ];
}
