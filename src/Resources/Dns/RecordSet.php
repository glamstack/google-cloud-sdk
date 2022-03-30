<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Faker\Provider\Base;
use Glamstack\GoogleCloud\Exceptions\RecordSetException;
use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Glamstack\GoogleCloud\Resources\Dns\Dns;
use Illuminate\Support\Facades\Log;

class RecordSet extends BaseClient
{

    const RECORD_SET_BODY = ['name', 'ttl', 'type', 'rrdatas'];

    public function list(string $managed_zone, array $request_data = []): object|string
    {
        return BaseClient::getRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets', []);
    }

    /**
     * @throws RecordSetException
     */
    public function create(string $managed_zone, array $request_data = []): object|string
    {
        $this->verifyRecordSet($request_data);
        return BaseClient::postRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets', $request_data);
    }

    public function delete(string $managed_zone, string $name, string $type): object|string
    {
        return BaseClient::deleteRequest('/' . $this->project_id . '/managedZones/' .
            $managed_zone . '/rrsets/' . $name . '/' . $type);
    }

    protected function verifyRecordSet(array $record_set){
        foreach (self::RECORD_SET_BODY as $parameter) {
            if (!array_key_exists($parameter, $record_set)) {
                $error_message = 'The ' . $parameter . ' is missing from the ' .
                    'provided `record_set` array. Please ensure that the ' .
                    '`record_set` includes the following values: [' .
                    implode(', ', self::RECORD_SET_BODY) . ']';

                Log::stack((array) config('glamstack-google.auth.log_channels'))
                    ->critical(
                        $error_message,
                        [
                            'event_type' => 'record-set-required-parameter-missing',
                            'class' => get_class(),
                            'status_code' => '501',
                            'message' => $error_message,
                            'missing_parameter' => $parameter
                        ]
                    );
                throw RecordSetException::create();
            }
        }
    }
}
