<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\Models\Dns\ManagedZoneModel;

class ManagedZone extends BaseClient
{

    private ManagedZoneModel $managedZoneModel;

    public function __construct(ApiClient $api_client)
    {
        parent::__construct($api_client);
        $this->managedZoneModel = new ManagedZoneModel();
    }

    public function get(string $zone_name, $optional_request_data = []): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id . '/managedZones/' . $zone_name, $optional_request_data);
    }

    public function list(): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id . '/managedZones');
    }

    public function create(array $request_data, array $optional_request_data = []): object|string
    {
        $this->managedZoneModel->verifyCreate($request_data);
        return BaseClient::postRequest('/' . $this->project_id . '/managedZones', $request_data);
    }

    public function delete(string $zone_name): object|string
    {
        return BaseClient::deleteRequest('/' . $this->project_id . '/managedZones/' . $zone_name);
    }

    public function update(string $managed_zone, array $request_data): object|string
    {
        return BaseClient::patchRequest('/' . $this->project_id .
            '/managedZones/' . $managed_zone, $request_data);
    }
}
