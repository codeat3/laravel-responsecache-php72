<?php

namespace Codeat3\ResponseCache\Facades;

use Illuminate\Support\Facades\Facade;

class ResponseCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'responsecache';
    }
}
