<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\Models\Dns\RecordSetModel;

class RecordSet extends BaseClient
{
    private RecordSetModel $recordSetModel;
    private string $base_url;

    public function __construct(ApiClient $api_client, string $base_url)
    {
        parent::__construct($api_client);
        $this->recordSetModel = new RecordSetModel();

        // Set the base_url class level variable
        $this->base_url = $base_url;
    }

    /**
     * Get a GCP record set
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets/get
     *
     * @param array $request_data
     *      Optional request parameters to pass into request body
     *
     * @return object|string
     *
     * @throws \Exception
     */
    public function get(
        array $request_data = []
    ): object|string {

        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->recordSetModel->get($request_data);

        return BaseClient::getRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone . '/rrsets/' .
            $request_data->path_parameters->name . '/' .
            $request_data->path_parameters->type, $request_data->request_data
        );
    }

    /**
     * List a GCP managed zone's records
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets/list
     *
     * @param array $request_data
     *      Optional request data to pass into the list request
     *
     * @return object|string
     *
     * @throws \Exception
     */
    public function list(array $request_data = []): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->recordSetModel->list($request_data);

        return BaseClient::getRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone . '/rrsets',
            $request_data->request_data
        );
    }

    /**
     * Create a new record set in a managed zone
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets/create
     * @see https://cloud.google.com/dns/docs/records-overview#supported_dns_record_types
     * @see https://datatracker.ietf.org/doc/html/rfc1035
     *
     * @param array $request_data
     *      Required record set properties for creation
     *      ```php
     *      [
     *          'managed_zone' => Required | (string)
     *          'name' => Required | (string) The name of the record set (ex. 'testingmail.testingzone.example.com.'),
     *          'type' => Required | (string) The type of record set (see records-overview#supported_dns_record_types reference),
     *          'rrdatas' => Required | (array) As defined in RFC 1035 (section 5) and RFC 1034 (section 3.6.1) (ex. ['mail.testingzone.example.com.'])
     *          'project_id' Optional | (string) The GCP project id of the record set (Default: GCP project id the SDK was initialized with)
     *          'ttl' => Optional | (int) The TTL of the record set (ex. 300)
     *      ]
     *      ```
     *
     * @return object|string
     *
     * @throws \Exception
     */
    public function create(array $request_data): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->recordSetModel->create($request_data);

        return BaseClient::postRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone . '/rrsets',
            $request_data->request_data
        );
    }

    /**
     * Delete a record set from a managed zone
     *
     * @param array $request_data
     *      Optional request data to pass into the DELETE request
     *
     * @return object|string
     *
     * @throws \Exception
     */
    public function delete(array $request_data = []): object|string
    {
        $request_data['project_id'] = $request_data['project_id'] ?? $this->project_id;

        $request_data = $this->recordSetModel->delete($request_data);

        return BaseClient::deleteRequest($this->base_url . '/' .
            $request_data->path_parameters->project_id . '/managedZones/' .
            $request_data->path_parameters->managed_zone . '/rrsets/' .
            $request_data->path_parameters->name . '/' .
            $request_data->path_parameters->type, $request_data->request_data
        );
    }
}
