<?php

namespace Glamstack\GoogleCloud\Resources\Rest;

use Glamstack\GoogleCloud\ApiClient;

class Rest extends ApiClient
{
    public function Method(): Method
    {
        return new Method($this);
    }
}