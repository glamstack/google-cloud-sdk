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

    /**
     * List a GCP managed zone's records
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets/list
     *
     * @param string $managed_zone
     *      The managed zone to list the record sets of
     *
     * @param array $request_data
     *      Optional request data to pass into the list request
     *
     * @return object|string
     */
    public function list(string $managed_zone, array $request_data = []): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets', $request_data);
    }

    /**
     * Create a new record set in a managed zone
     *
     * Required Parameters:
     * ```php
     * [
     *      'name' => (string) The name of the record set (ex. 'testingmail.testingzone.example.com.'),
     *      'type' => (string) The type of record set (see records-overview#supported_dns_record_types reference),
     *      'ttl' => (int) The TTL of the record set (ex. 300)
     *      'rrdatas' => (array) As defined in RFC 1035 (section 5) and RFC 1034 (section 3.6.1) (ex. ['mail.testingzone.example.com.'])
     * ]
     * ```
     *
     * @see https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets/create
     *
     * @see https://cloud.google.com/dns/docs/records-overview#supported_dns_record_types
     *
     * @see https://datatracker.ietf.org/doc/html/rfc1035
     *
     * @param string $managed_zone
     *      The managed zone to create the record set in
     *
     * @param array $request_data
     *      Required record set properties for creation
     *
     * @param array $optional_request_data
     *      Optional record set properties to set
     *
     * @return object|string
     */
    public function create(string $managed_zone, array $request_data = [], array $optional_request_data = []): object|string
    {
        $this->recordSetModel->verifyCreate($request_data);

        $request_data = array_merge($request_data, $optional_request_data);

        return BaseClient::postRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets', $request_data);
    }

    /**
     * Delete a record set from a managed zone
     *
     * @param string $managed_zone
     *      The managed zone to delete the record set from
     *
     * @param string $name
     *      The record set name to be deleted
     *
     * @param string $type
     *      The type of record to be deleted (ex. "CNAME")
     *
     * @param array $request_data
     *      Optional request data to pass into the DELETE request
     *
     * @return object|string
     */
    public function delete(string $managed_zone, string $name, string $type, array $request_data = []): object|string
    {
        return BaseClient::deleteRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets/' . $name . '/' . $type, $request_data);
    }
}
