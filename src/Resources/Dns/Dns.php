<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;

class Dns extends ApiClient
{
    public function RecordSet(): RecordSet
    {
        return new RecordSet($this);
    }

    public function ManagedZone(): ManagedZone
    {
        return new ManagedZone($this);
    }
}
