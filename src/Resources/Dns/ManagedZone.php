<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\Models\Dns\ManagedZoneModel;

class ManagedZone extends BaseClient
{
    const BASE_URL = 'https://dns.googleapis.com/dns/v1/projects';

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
        return BaseClient::getRequest(self::BASE_URL . '/' . $this->project_id .
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
        return BaseClient::getRequest(self::BASE_URL . '/' . $this->project_id .
            '/managedZones', $optional_request_data);
    }

    /**
     * Create a new managed zone in the Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/create
     *
     * @param array $request_data
     *      Required managed zone properties for creation
     * 
     *      ```php
     *      [
     *          'name' => (string) GCP Managed Zone name,
     *          'dns_name' => (string) The DNS name of this managed zone with a trailing period (ex. "example.com."),
     *          'visibility' => (string) The zone's visibility: public zones are exposed to the Internet while private zones are visible only to Virtual Private Cloud resources. ("public" or "private"),
     *          'dnssec_config_state' => (string) DNSSEC configuration ("on" or "off"),
     *          'description' => (string) A short description of the managed zone
     *      ]
     *      ```
     *
     * @param array $optional_request_data
     *      Optional extra properties for the managed zone creation
     *
     * @return object|string
     */
    public function create(array $request_data, array $optional_request_data = []): object|string
    {
        // Verify all required parameters are passed in
        $this->managedZoneModel->verifyCreate($request_data);

        // Merge the required request data with the optional request data
        $request_data = array_merge($request_data, $optional_request_data);

        return BaseClient::postRequest(self::BASE_URL . '/' . $this->project_id .
            '/managedZones', $request_data);
    }

    /**
     * Update a managed zone of a Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/update
     *
     * @param string $managed_zone
     *      The managed zone to update
     *
     * @param array $request_data
     *      The managed zone properties to update
     *
     * @return object|string
     */
    public function update(string $managed_zone, array $request_data): object|string
    {
        return BaseClient::patchRequest(self::BASE_URL . '/' . $this->project_id .
            '/managedZones/' . $managed_zone, $request_data);
    }

    /**
     * Delete a managed zone from a Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/delete
     *
     * @param string $managed_zone
     *      The name of the managed zone to delete
     *
     * @param array $request_data
     *      Optional request data to pass into the DELETE request
     *
     * @return object|string
     */
    public function delete(string $managed_zone, array $request_data = []): object|string
    {
        return BaseClient::deleteRequest(self::BASE_URL . '/' . $this->project_id .
            '/managedZones/' . $managed_zone, $request_data);
    }
}
