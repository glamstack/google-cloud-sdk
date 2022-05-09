<?php

namespace Glamstack\GoogleCloud\Resources\Rest;

use Exception;
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
     * Example Usage:
     * ```php
     * $client = new Glamstack\GoogleCloud\ApiClient('test');
     * $response = $client->rest()->get('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones', []);
     * ```
     *
     * Example Response:
     * ```php
     * {#516
     *   +"headers": {#461
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "X-Origin Referer Origin,Accept-Encoding"
     *     +"Date": "Mon, 09 May 2022 17:29:17 GMT"
     *     +"Server": "ESF"
     *     +"Cache-Control": "private"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *     +"Accept-Ranges": "none"
     *     +"Transfer-Encoding": "chunked"
     *   }
     *   +"json": "{"name":"example-zone","dnsName":"examplezone.example.com.","description":"","id":"1234567890","nameServers":["example.googledomains.com."],"creationTime":"2022-03-03T13:54:00.009Z","visibility":"private","cloudLoggingConfig":{"kind":"dns#managedZoneCloudLoggingConfig"},"kind":"dns#managedZone"}"
     *   +"object": {#500
     *     +"name": "example-zone"
     *     +"dnsName": "examplezone.example.com."
     *     +"description": ""
     *     +"id": "1234567890"
     *     +"nameServers": array:1 [
     *       0 => "example.googledomains.com."
     *     ]
     *     +"creationTime": "2022-03-03T13:54:00.009Z"
     *     +"visibility": "private"
     *     +"cloudLoggingConfig": {#473
     *       +"kind": "dns#managedZoneCloudLoggingConfig"
     *     }
     *     +"kind": "dns#managedZone"
     *   }
     *   +"status": {#499
     *     +"code": 200
     *     +"ok": true
     *     +"successful": true
     *     +"failed": false
     *     +"serverError": false
     *     +"clientError": false
     *   }
     * }
     * ```
     * @param string $uri
     *      The Google URI to run the GET request with
     *
     * @param array $request_data
     *      Request data to load into GET request `Request Body`
     *
     * @return object|string
     *
     * @throws Exception
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
     * Example Usage:
     * ```php
     * $client = new Glamstack\GoogleCloud\ApiClient('test');
     * $response = $client->rest()->post('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones', $request_data);
     * ```
     *
     * Example Response:
     * ```php
     * {#516
     *   +"headers": {#461
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "X-Origin Referer Origin,Accept-Encoding"
     *     +"Date": "Mon, 09 May 2022 17:29:17 GMT"
     *     +"Server": "ESF"
     *     +"Cache-Control": "private"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *     +"Accept-Ranges": "none"
     *     +"Transfer-Encoding": "chunked"
     *   }
     *   +"json": "{"name":"example-zone","dnsName":"examplezone.example.com.","description":"","id":"1234567890","nameServers":["example.googledomains.com."],"creationTime":"2022-03-03T13:54:00.009Z","visibility":"private","cloudLoggingConfig":{"kind":"dns#managedZoneCloudLoggingConfig"},"kind":"dns#managedZone"}"
     *   +"object": {#500
     *     +"name": "example-zone"
     *     +"dnsName": "examplezone.example.com."
     *     +"description": ""
     *     +"id": "1234567890"
     *     +"nameServers": array:1 [
     *       0 => "example.googledomains.com."
     *     ]
     *     +"creationTime": "2022-03-03T13:54:00.009Z"
     *     +"visibility": "private"
     *     +"cloudLoggingConfig": {#473
     *       +"kind": "dns#managedZoneCloudLoggingConfig"
     *     }
     *     +"kind": "dns#managedZone"
     *   }
     *   +"status": {#499
     *     +"code": 200
     *     +"ok": true
     *     +"successful": true
     *     +"failed": false
     *     +"serverError": false
     *     +"clientError": false
     *   }
     * }
     * ```
     * @param string $uri
     *      The Google URI to run the POST request with
     *
     * @param array $request_data
     *      Request data to load into POST request `Request Body`
     *
     * @return object|string
     * @throws Exception
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
     * Example Usage:
     * ```php
     * $client = new Glamstack\GoogleCloud\ApiClient('test');
     * $response = $client->rest()->post('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones', $request_data);
     * ```
     *
     * Example Response:
     * ```php
     * {#516
     *   +"headers": {#461
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "X-Origin Referer Origin,Accept-Encoding"
     *     +"Date": "Mon, 09 May 2022 17:29:17 GMT"
     *     +"Server": "ESF"
     *     +"Cache-Control": "private"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *     +"Accept-Ranges": "none"
     *     +"Transfer-Encoding": "chunked"
     *   }
     *   +"json": "{"name":"example-zone","dnsName":"examplezone.example.com.","description":"","id":"1234567890","nameServers":["example.googledomains.com."],"creationTime":"2022-03-03T13:54:00.009Z","visibility":"private","cloudLoggingConfig":{"kind":"dns#managedZoneCloudLoggingConfig"},"kind":"dns#managedZone"}"
     *   +"object": {#500
     *     +"name": "example-zone"
     *     +"dnsName": "examplezone.example.com."
     *     +"description": ""
     *     +"id": "1234567890"
     *     +"nameServers": array:1 [
     *       0 => "example.googledomains.com."
     *     ]
     *     +"creationTime": "2022-03-03T13:54:00.009Z"
     *     +"visibility": "private"
     *     +"cloudLoggingConfig": {#473
     *       +"kind": "dns#managedZoneCloudLoggingConfig"
     *     }
     *     +"kind": "dns#managedZone"
     *   }
     *   +"status": {#499
     *     +"code": 200
     *     +"ok": true
     *     +"successful": true
     *     +"failed": false
     *     +"serverError": false
     *     +"clientError": false
     *   }
     * }
     * ```
     *
     * @param string $uri
     *      The Google URI to run the PATCH request with
     *
     * @param array $request_data
     *      Request data to load into PATCH request `Request Body`
     *
     * @return object|string
     * @throws Exception
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
     * @throws Exception
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
     * Example Usage:
     * ```php
     * $client = new Glamstack\GoogleCloud\ApiClient('test');
     * $response = $client->rest()->delete('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones/testing-zone-3');
     * ```
     *
     * Example Response:
     * ```php
     * {#599
     *   +"headers": {#597
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "X-Origin Referer Origin,Accept-Encoding"
     *     +"Date": "Mon, 09 May 2022 18:27:46 GMT"
     *     +"Server": "ESF"
     *     +"Cache-Control": "private"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": "h3=":443"; ma=2592000,h3-29=":443"; ma=2592000,h3-Q050=":443"; ma=2592000,h3-Q046=":443"; ma=2592000,h3-Q043=":443"; ma=2592000,quic=":443"; ma=2592000; v="46,43""
     *     +"Accept-Ranges": "none"
     *     +"Transfer-Encoding": "chunked"
     *   }
     *   +"json": "[]"
     *   +"object": {#569}
     *   +"status": {#592
     *     +"code": 200
     *     +"ok": true
     *     +"successful": true
     *     +"failed": false
     *     +"serverError": false
     *     +"clientError": false
     *   }
     * }
     * ```
     * @param string $uri
     *      The Google URI to run the DELETE request with
     *
     * @param array $request_data
     *      Request data to load into DELETE request `Request Body`
     *
     * @return object|string
     * @throws Exception
     */
    public function delete(string $uri, array $request_data = []): object|string
    {
        $method = new Method($this);
        return $method->delete($uri, $request_data);
    }
}