<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\Models\Dns\ManagedZoneModel;

class ManagedZone extends BaseClient
{
    private string $base_url;

    private ManagedZoneModel $managedZoneModel;

    public function __construct(ApiClient $api_client, string $base_url)
    {
        parent::__construct($api_client);
        $this->managedZoneModel = new ManagedZoneModel();
        $this->base_url = $base_url;
    }

    /**
     * Get a managed zone's information
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/get
     *
     * @param array $request_data
     *      Request data to send to the managed zone GET request
     *
     * @return object|string
     * @throws \Exception
     */
    public function get(array $request_data = []): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->managedZoneModel->get($request_data);

        return BaseClient::getRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone,
            $request_data->request_data
        );
    }

    /**
     * List the managed zones of a Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/list
     *
     * @param array $request_data
     *      Optional request data to use with list
     *
     * @return object|string
     */
    public function list(array $request_data = []): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->managedZoneModel->list($request_data);

        return BaseClient::getRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones',
            $request_data->request_data
        );
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
     *          'logging_enabled' => (bool) Enable or Disable Logging
     *          'description' => (string) A short description of the managed zone
     *      ]
     *      ```
     *
     * @return object|string
     * @throws \Exception
     */
    public function create(array $request_data): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        // Verify all required parameters are passed in
        $request_data = $this->managedZoneModel->create($request_data);
        return BaseClient::postRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones',
            $request_data->request_data
        );
    }

    /**
     * Update a managed zone of a Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/update
     *
     * @param array $request_data
     *      The managed zone properties to update
     *
     * @return object|string
     * @throws \Exception
     */
    public function update(array $request_data): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->managedZoneModel->update($request_data);

        return BaseClient::patchRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone,
            $request_data->request_data
        );
    }

    /**
     * Delete a managed zone from a Google Project
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/managedZones/delete
     *
     * @param array $request_data
     *      Optional request data to pass into the DELETE request
     *
     * @return object|string
     * @throws \Exception
     */
    public function delete(array $request_data = []): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->managedZoneModel->delete($request_data);

        return BaseClient::deleteRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone,
            $request_data->request_data
        );
    }
}
