<?php

namespace Glamstack\GoogleCloud\Exceptions;

use Exception;

class ManagedZoneException extends Exception
{
    public static function create(): static
    {
        return new static('The Managed Zone is missing a required parameter');
    }
}
