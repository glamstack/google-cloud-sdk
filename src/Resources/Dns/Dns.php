<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;

class Dns extends ApiClient
{
    const BASE_URL = "https://dns.googleapis.com/dns/v1/projects";
    /**
     * Creates a RecordSet object
     *
     * @return RecordSet
     */
    public function RecordSet(): RecordSet
    {
        return new RecordSet($this, self::BASE_URL);
    }

    /**
     * Creates a ManagedZone object
     *
     * @return ManagedZone
     */
    public function ManagedZone(): ManagedZone
    {
        return new ManagedZone($this, self::BASE_URL);
    }
}
