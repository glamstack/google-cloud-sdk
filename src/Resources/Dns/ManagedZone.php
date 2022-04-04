<?php

namespace Glamstack\GoogleCloud\Resources\Dns;

use Crypto\Base64;
use Faker\Provider\Base;
use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Exceptions\ManagedZoneException;
use Glamstack\GoogleCloud\Resources\BaseClient;
use Illuminate\Support\Facades\Log;
use Glamstack\GoogleCloud\Resources\Dns\ManagedZoneModel;

class ManagedZone extends BaseClient
{

    private ManagedZoneModel $managedZoneModel;

    public function __construct(ApiClient $api_client)
    {
        parent::__construct($api_client);
        $this->managedZoneModel = new ManagedZoneModel();
    }

    const MANAGED_ZONE_OPTIONAL_BODY = ['name', 'visibility', 'dnsName', 'dnssecConfig.state',
        'cloudLoggingConfig.enableLogging'];

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

    protected function verifyManagedZoneOptionalData(array $managed_zone){
        foreach (self::MANAGED_ZONE_OPTIONAL_BODY as $parameter) {
            if (!array_key_exists($parameter, $managed_zone)) {
                $error_message = 'The ' . $parameter . ' is missing from the ' .
                    'provided `managed_zone` array. Please ensure that the ' .
                    '`managed_zone` includes the following values: [' .
                    implode(', ', self::MANAGED_ZONE_OPTIONAL_BODY) . ']';

                Log::stack((array) config('glamstack-google.auth.log_channels'))
                    ->critical(
                        $error_message,
                        [
                            'event_type' => 'managed-zone-set-required-parameter-missing',
                            'class' => get_class(),
                            'status_code' => '501',
                            'message' => $error_message,
                            'missing_parameter' => $parameter
                        ]
                    );
                throw ManagedZoneException::create();
            }
        }
    }
}
