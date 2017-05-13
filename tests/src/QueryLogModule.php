<?php

namespace __Test;

use Ytake\LaravelAspect\Modules\QueryLogModule as QueryLog;

class QueryLogModule extends QueryLog
{
    /**
     * @var array
     */
    protected $classes = [
        AspectQueryLog::class
    ];
}
