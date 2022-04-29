<?php

namespace Glamstack\GoogleCloud\Resources\Rest;

use Glamstack\GoogleCloud\ApiClient;

class Rest extends ApiClient
{
    public function get(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->get($uri, $request_data);
    }

    public function post(string $uri,array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->post($uri, $request_data);
    }

    public function patch(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->patch($uri, $request_data);
    }

    public function put(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->put($uri, $request_data);
    }

    public function delete(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->delete($uri, $request_data);
    }
}