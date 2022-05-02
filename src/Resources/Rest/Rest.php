<?php

namespace Glamstack\GoogleCloud\Resources\Rest;

use Glamstack\GoogleCloud\ApiClient;

class Rest extends ApiClient
{

    /**
     * GET HTTP Request
     *
     * Will run a GET request against the provided `uri`
     *
     * > **NOTE:** There is no validation in the provided uri or request data of
     * this method.
     * 
     * @param string $uri
     *      The Google URI to run the GET request with
     *
     * @param array $request_data
     *      Request data to load into GET request `Request Body`
     *
     * @return object|string
     */
    public function get(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->get($uri, $request_data);
    }

    /**
     * POST HTTP Request
     *
     * Will run a POST request against the provided `uri`
     *
     * > **NOTE:** There is no validation in the provided uri or request data of
     * this method.
     *
     * @param string $uri
     *      The Google URI to run the POST request with
     *
     * @param array $request_data
     *      Request data to load into POST request `Request Body`
     *
     * @return object|string
     */
    public function post(string $uri,array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->post($uri, $request_data);
    }

    /**
     * PATCH HTTP Request
     *
     * Will run a PATCH request against the provided `uri`
     *
     * > **NOTE:** There is no validation in the provided uri or request data of
     * this method.
     *
     * @param string $uri
     *      The Google URI to run the PATCH request with
     *
     * @param array $request_data
     *      Request data to load into PATCH request `Request Body`
     *
     * @return object|string
     */
    public function patch(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->patch($uri, $request_data);
    }

    /**
     * PUT HTTP Request
     *
     * Will run a PUT request against the provided `uri`
     *
     * > **NOTE:** There is no validation in the provided uri or request data of
     * this method.
     *
     * @param string $uri
     *      The Google URI to run the PUT request with
     *
     * @param array $request_data
     *      Request data to load into PUT request `Request Body`
     *
     * @return object|string
     */
    public function put(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->put($uri, $request_data);
    }

    /**
     * DELETE HTTP Request
     *
     * Will run a DELETE request against the provided `uri`
     *
     * > **NOTE:** There is no validation in the provided uri or request data of
     * this method.
     *
     * @param string $uri
     *      The Google URI to run the DELETE request with
     *
     * @param array $request_data
     *      Request data to load into DELETE request `Request Body`
     *
     * @return object|string
     */
    public function delete(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->delete($uri, $request_data);
    }
}