<?php

namespace __Test;

use Bssd\LaravelAspect\Modules\QueryLogModule as QueryLog;

class QueryLogModule extends QueryLog
{
    /**
     * @var array
     */
    protected $classes = [
        AspectQueryLog::class
    ];
}
