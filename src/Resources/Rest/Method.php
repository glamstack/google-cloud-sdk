<?php

namespace Glamstack\GoogleCloud\Resources\Rest;

use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Resources\BaseClient;

class Method extends BaseClient
{
    public function get(string $uri, array $request_data = []): object|string
    {
        return BaseClient::getRequest($uri, $request_data);
    }

    public function post(string $uri, array $request_data = []): object|string
    {
        return BaseClient::postRequest($uri, $request_data);
    }

    public function patch(string $uri, array $request_data = []): object|string
    {
        return BaseClient::patchRequest($uri, $request_data);
    }

    public function put(string $uri, array $request_data = []): object|string
    {
        return BaseClient::putRequest($uri, $request_data);
    }

    public function delete(string $uri, array $request_data = []): object|string
    {
        return BaseClient::deleteRequest($uri, $request_data);
    }
}