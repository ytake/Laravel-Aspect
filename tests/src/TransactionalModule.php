<?php

namespace __Test;

use Ytake\LaravelAspect\Modules\TransactionalModule as Transactional;

class TransactionalModule extends Transactional
{
    /**
     * @var array
     */
    protected $classes = [
        \__Test\AspectTransactionalDatabase::class,
        \__Test\AspectTransactionalString::class
    ];
}
