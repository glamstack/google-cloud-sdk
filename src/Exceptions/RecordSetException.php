<?php

namespace Glamstack\GoogleCloud\Exceptions;

use Exception;

class RecordSetException extends Exception
{
    public static function create(): static
    {
        return new static('The Record Set is missing a required parameter');
    }
}
