<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\Models\Dns\RecordSetModel;

class RecordSet extends BaseClient
{

    private RecordSetModel $recordSetModel;

    public function __construct(ApiClient $api_client)
    {
        parent::__construct($api_client);
        $this->recordSetModel = new RecordSetModel();
    }

    public function list(string $managed_zone, array $request_data = []): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets', []);
    }

    /**
     * @param string $managed_zone
     * @param array  $request_data
     *
     * @return object|string
     */
    public function create(string $managed_zone, array $request_data = []): object|string
    {
        $this->recordSetModel->verifyCreate($request_data);
        return BaseClient::postRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets', $request_data);
    }

    public function delete(string $managed_zone, string $name, string $type): object|string
    {
        return BaseClient::deleteRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets/' . $name . '/' . $type);
    }
}
