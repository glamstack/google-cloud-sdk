<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\Dns\RecordSet;

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
