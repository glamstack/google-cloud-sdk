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

    /**
     * Get a managed zone's information
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/get
     *
     * @param string $managed_zone
     *      The `ID` or `Name` of the managed zone to get information of
     *
     * @param array $optional_request_data
     *      Optional request data to use with the GET request.
     *      i.e. Utilizing the `fields` parameter
     * 
     * @return object|string
     */
    public function get(string $managed_zone, array $optional_request_data = []): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id .
            '/managedZones/' . $managed_zone, $optional_request_data);
    }

    /**
     * List the managed zones of a Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/list
     *
     * @param array $optional_request_data
     *      Optional request data to use with list
     *
     * @return object|string
     */
    public function list(array $optional_request_data = []): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id .
            '/managedZones', $optional_request_data);
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
