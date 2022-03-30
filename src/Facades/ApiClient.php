<?php

namespace Glamstack\GoogleCloud\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Glamstack\GoogleCloud\ApiClient
 */
class ApiClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'apiclient';
    }
}
