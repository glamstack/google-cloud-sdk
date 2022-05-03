<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;

class Dns extends ApiClient
{
    /**
     * Creates a RecordSet object
     *
     * @return RecordSet
     */
    public function RecordSet(): RecordSet
    {
        return new RecordSet($this);
    }

    /**
     * Creates a ManagedZone object
     *
     * @return ManagedZone
     */
    public function ManagedZone(): ManagedZone
    {
        return new ManagedZone($this);
    }
}
