<?php

namespace Glamstack\GoogleCloud\Resources;

use Exception;
use Glamstack\GoogleCloud\ApiClient;
use Glamstack\GoogleCloud\Traits\ResponseLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class BaseClient
{
    use ResponseLog;

    public const CONFIG_PATH = 'glamstack-google-cloud.';

    private string $auth_token;
    protected string $project_id;
    protected array $log_channels;
    private ApiClient $api_client;

    /**
     * @throws \Exception
     */
    public function __construct(
        ApiClient $api_client
    ) {
        // Initialize Google Auth SDK
        $this->api_client = $api_client;

        // Set the Google Project ID
        $this->setProjectId();

        // Set the log_channels class variable
        $this->setLogChannels();

        if ($this->api_client->connection_key) {
            $google_auth = new \Glamstack\GoogleAuth\AuthClient(
                $this->parseConfigFile($this->api_client->connection_key)
            );
        } else {
            $google_auth = new \Glamstack\GoogleAuth\AuthClient(
                $this->parseConnectionConfigArray($this->api_client->connection_config)
            );
        }

        // Authenticate with Google OAuth2 Server auth_token
        try {
            $this->auth_token = $google_auth->authenticate();
        } catch (Exception $exception) {
            $this->logLocalError($exception);
            throw $exception;
        }
    }

    /**
     * Set the log_channels class variable
     *
     * @return void
     */
    protected function setLogChannels(): void
    {
        if ($this->api_client->connection_key) {
            $this->log_channels = config(
                self::CONFIG_PATH . 'connections.' .
                $this->api_client->connection_key . '.log_channels'
            );
        } else {
            $this->log_channels = $this->api_client->connection_config['log_channels'];
        }
    }

    /**
     * Parse the configuration file to get config parameters
     *
     * @param string $connection_key
     *      The connection key provided during initialization of the SDK
     *
     * @return array
     */
    protected function parseConfigFile(string $connection_key): array
    {
        return [
            'api_scopes' => $this->getConfigApiScopes($connection_key),
            'subject_email' => $this->getConfigSubjectEmail($connection_key),
            'json_key_file_path' => $this->getConfigJsonFilePath($connection_key)
        ];
    }

    /**
     * Get the api_scopes from the configuration file
     *
     * @param string $connection_key
     *     The connection key provided during initialization of the SDK
     *
     * @return array
     */
    protected function getConfigApiScopes(string $connection_key): array
    {
        return config(self::CONFIG_PATH . 'connections.' . $connection_key .
            '.api_scopes');
    }

    /**
     * Get the subject_email from the configuration file
     *
     * Subject email is not required so if not set then return null
     *
     * @param string $connection_key
     *      The connection key provided during initialization of the SDK
     *
     * @return string|null
     */
    protected function getConfigSubjectEmail(string $connection_key): string|null
    {
        $config_path = self::CONFIG_PATH . 'connections.' . $connection_key;
        if (array_key_exists('subject_email', config($config_path))) {
            return config($config_path . '.subject_email');
        } else {
            return null;
        }
    }

    /**
     * Get the json_key_file from the configuration file
     *
     * This is required if using the configuration file
     *
     * @param string $connection_key
     *      The connection key provided during initialization of the SDK
     *
     * @return string
     */
    protected function getConfigJsonFilePath(string $connection_key): string
    {
        return config(self::CONFIG_PATH . 'connections.' . $connection_key .
            '.json_key_file_path');
    }

    /**
     * Parse the connection_config array to get the configuration parameters
     *
     * @param array $connection_config
     *      The connection config array provided during initialization of the SDK
     *
     * @return array
     */
    protected function parseConnectionConfigArray(array $connection_config): array
    {
        return [
            'api_scopes' => $this->getConfigArrayApiScopes($connection_config),
            'subject_email' => $this->getConfigArraySubjectEmail($connection_config),
            'json_key_file_path' => $this->getConfigArrayFilePath($connection_config),
            'json_key' => $this->getConfigArrayJsonKey($connection_config)
        ];
    }

    /**
     * Get the api_scopes from the connection_config array
     *
     * @param array $connection_config
     *      The connection config array provided during initialization of the SDK
     *
     * @return array
     */
    protected function getConfigArrayApiScopes(array $connection_config): array
    {
        return $connection_config['api_scopes'];
    }

    /**
     * Get the subject_email from the connection_config array
     *
     * Subject Email is not required so if not set return null
     *
     * @param array $connection_config
     *      The connection config array provided during initialization of the SDK
     *
     * @return string|null
     */
    protected function getConfigArraySubjectEmail(array $connection_config): string|null
    {
        if (array_key_exists('subject_email', $connection_config)) {
            return $connection_config['subject_email'];
        } else {
            return null;
        }
    }

    /**
     * Get the file_path from the connection_config array
     *
     * file_path is not required to be set so if not set return null
     *
     * @param array $connection_config
     *      The connection config array provided during initialization of the SDK
     *
     * @return string|null
     */
    protected function getConfigArrayFilePath(array $connection_config): string|null
    {
        if (array_key_exists('json_key_file_path', $connection_config)) {
            return $connection_config['json_key_file_path'];
        } else {
            return null;
        }
    }

    /**
     * Get the json_key from the connection_config array
     *
     * json_key i9s not required to be set so if not set return null
     *
     * @param array $connection_config
     *      The connection config array provided during initialization of the SDK
     *
     * @return mixed|null
     */
    protected function getConfigArrayJsonKey(array $connection_config): mixed
    {
        if (array_key_exists('json_key', $connection_config)) {
            return $connection_config['json_key'];
        } else {
            return null;
        }
    }

    /**
     * Set the project_id class level variable
     *
     * @return void
     */
    protected function setProjectId(): void
    {
        if ($this->api_client->connection_key) {
            $this->project_id = config(
                self::CONFIG_PATH . 'connections.' .
                $this->api_client->connection_key . '.project_id'
            );
        } else {
            $this->project_id = $this->api_client->connection_config['project_id'];
        }
    }

    /**
     * Google API GET Request
     *
     * @param string $uri The URI of the Google Cloud API
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Cloud API GET request
     *
     * @return object|string
     */
    public function getRequest(string $uri, array $request_data = []): object|string
    {
        $response = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->get($uri, $request_data);

        // Check if the data is paginated
        $isPaginated = $this->checkForPagination($response);

        if ($isPaginated) {

            // Get the paginated results
            $paginated_results = $this->getPaginatedResults($uri, $request_data, $response);

            // The $paginated_results will be returned as an object of objects
            // which needs to be converted to a flat object for standardizing
            // the response returned. This needs to be a separate function
            // instead of casting to an object due to return body complexities
            // with nested array and object mixed notation.
            $response->paginated_results = $this->convertPaginatedResponseToObject($paginated_results);

            // Unset the body and json elements of the original Guzzle Response
            // Object. These will be reset with the paginated results.
            unset($response->body);
            unset($response->json);
        }

        // Parse the API response and return a Glamstack standardized response
        $parsed_api_response = $this->parseApiResponse($response, $isPaginated);

        $this->logResponse($uri, $parsed_api_response);
        return $parsed_api_response;
    }

    /**
     * Google Cloud API POST Request. Google will utilize POST request for
     * inserting a new resource.
     *
     * This method is called from other services to perform a POST request and
     * return a structured object.
     *
     * @param string $uri The URI of the Google Cloud API request with
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Cloud API POST request
     *
     * @return object|string
     */
    public function postRequest(string $uri, ?array $request_data = []): object|string
    {
        // Append to Google Domain and Google Customer ID to the request data
        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->post($uri, $request_data);

        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        $this->logResponse($uri, $response);

        return $response;
    }

    /**
     * Google Cloud API PATCH Request
     *
     * @param string $uri The URI of the Google Cloud API request with
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Cloud API POST request
     *
     * @return object|string
     */
    public function patchRequest(string $uri, array $request_data = []): object|string
    {
        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->patch($uri, $request_data);

        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        $this->logResponse($uri, $response);

        return $response;
    }

    /**
     * Google Cloud API PUT Request.
     *
     * This method is called from other services to perform a PUT request and
     * return a structured object
     *
     * @param string $uri The URI of the Google Cloud API request
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Cloud API PUT request
     *
     * @return object|string
     */
    public function putRequest(string $uri, array $request_data = []): object|string
    {
        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->put($uri, $request_data);

        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        $this->logResponse($uri, $response);

        return $response;
    }

    /**
     * Google Cloud API DELETE Request
     *
     * This method is called from other services to perform a DELETE request
     * and return a structured object.
     *
     * @param string $uri The URI of the Google Cloud API request
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Cloud API DELETE request
     *
     * @return object|string
     */
    public function deleteRequest(string $uri, array $request_data = []): object|string
    {
        // Append to Google Domain and Google Customer ID to the request data

        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->delete($uri, $request_data);

        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        $this->logResponse($uri, $response);

        return $response;
    }

    /**
     * Check if pagination is used in the Google Cloud GET response.
     *
     * @param Response $response API response from Google Cloud GET request
     *
     * @return bool True if pagination is required | False if not
     * @see GOOGLE PAGINATION EXAMPLE
     *
     */
    protected function checkForPagination(Response $response): bool
    {
        // Check if Google Cloud GET Request object contains `nextPageToken`
        if (property_exists($response->object(), 'nextPageToken')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Helper method for getting Google Cloud GET responses that require
     * pagination
     *
     * @param string $uri The URI of the Google Cloud API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data Request data to send with the Google
     * Cloud API GET request
     *
     * @param Response $response API response from Google Cloud GET request
     *
     * @return array
     */
    protected function getPaginatedResults(
        string $uri,
        array $request_data,
        Response $response
    ): array {
        // Initialize $records as an empty array. This is where we will store
        // the returned data from each paginated request.
        $records = [];

        // Collect the response body from the initial GET request's response
        $response_body = collect($this->getResponseBody($response))->flatten();

        // Merge the initial GET request's response into the $records array
        $records = array_merge($records, $response_body->toArray());

        // Get the next page using the initial responses `nextPageToken` element
        $next_response = $this->getNextPageResults(
            $uri,
            $request_data,
            $response
        );

        // Collect the response body from the subsequent GET request's response
        $next_response_body = collect(
            $this->getResponseBody($next_response)
        )->flatten();

        // Add the $next_response_body to the records array
        $records = array_merge($records, $next_response_body->toArray());

        // Check if there are more pages to GET
        $next_page_exists = $this->checkForPagination($next_response);

        // If there are more pages to GET, set the `$next_page_token` variable
        // to the `$next_response` `nextPageToken` element of the object
        if ($next_page_exists) {
            $next_page_token = $this->getNextPageToken($next_response);
        } else {
            $next_page_token = null;
        }

        // FIXME: Evaluate a do/while refactor based on okta-sdk

        // If there is an additional (ex. third) page then continue through all
        // data until the API response does not contain the `nextPageToken`
        // element in the returned object
        if ($next_page_token) {
            $next_response = $this->getNextPageResults(
                $uri,
                $request_data,
                $next_response
            );

            // Collect the response body from the subsequent GET request's response
            $next_response_body = collect(
                $this->getResponseBody($next_response)
            )->flatten();

            // Set the `next_response_body` to an array
            $next_response_body_array = $next_response_body->toArray();

            // Add the `next_response_body` array to the `records` array
            $records = array_merge($records, $next_response_body_array);

            // Check if there is another page
            $next_page_exists = $this->checkForPagination($next_response);

            // If there is another page set the `next_page_token` variable
            // to the `nextPageToken` from the response.
            if ($next_page_exists) {
                $this->getNextPageToken($next_response);
            }
        }

        return $records;
    }

    /**
     * Helper method to get the `nextPageToken` element from the GET Response
     * object
     *
     * @see https://cloud.google.com/apis/design/design_patterns#list_pagination
     *
     * @param Response $response Google Cloud API GET Request Guzzle
     * response
     *
     * @return string
     */
    protected function getNextPageToken(Response $response): string
    {
        return $response->object()->nextPageToken;
    }

    /**
     * Helper function to get the next page of a Google Cloud API GET
     * request.
     *
     * @param string $uri The URI of the Google Cloud API request
     *
     * @param array $request_data Request data to send with the Google
     * Cloud API GET request.
     *
     * @param Response $response API response from Google Cloud GET request
     *
     * @return Response
     */
    protected function getNextPageResults(
        string $uri,
        array $request_data,
        Response $response
    ): Response {

        // Set the Google Cloud Query parameter `pageToken` to the
        // responses `nextPageToken` element
        $next_page = [
            'pageToken' => $this->getNextPageToken($response)
        ];

        // Merge the `request_data` with the `next_page` this tells Google
        // Cloud that we are working with a paginated response
        $request_body = array_merge($request_data, $next_page);

        $records = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->get($uri, $request_body);

        return $records;
    }

    /**
     * Helper method to get just the response data from the Response object
     *
     * @param Response $response API response from Google Cloud GET request
     *
     * @return object
     */
    protected function getResponseBody(Response $response): object
    {
        // Check if the response object contains the `nextPageToken` element
        $contains_next_page = $this->checkForPagination($response);

        // Get the response object
        $response_object = $response->object();

        // Unset unnecessary elements
        unset($response_object->kind);
        unset($response_object->etag);

        // If the response contains the `nextPageToken` element unset that
        if ($contains_next_page) {
            unset($response_object->nextPageToken);
        }

        return $response_object;
    }

    /**
     * Convert API Response Headers to Object
     * This method is called from the parseApiResponse method to prettify the
     * Guzzle Headers that are an array with nested array for each value, and
     * converts the single array values into strings and converts to an object
     * for easier and consistent accessibility with the parseApiResponse format.
     *
     * Example $header_response:
     * ```php
     * [
     *   "ETag" => [
     *     ""nMRgLWac8h8NyH7Uk5VvV4DiNp4uxXg5gNUd9YhyaJE/dky_PFyA8Zq0WLn1WqUCn_A8oes""
     *   ]
     *   "Content-Type" => [
     *     "application/json; charset=UTF-8"
     *   ]
     *   "Vary" => [
     *     "Origin"
     *     "X-Origin"
     *     "Referer"
     *   ]
     *   "Date" => [
     *      "Mon, 24 Jan 2022 15:39:46 GMT"
     *   ]
     *   "Server" => [
     *     "ESF"
     *   ]
     *   "Content-Length" => [
     *     "355675"
     *   ]
     *   "X-XSS-Protection" => [
     *     "0"
     *   ]
     *   "X-Frame-Options" => [
     *     "SAMEORIGIN"
     *   ]
     *   "X-Content-Type-Options" => [
     *     "nosniff"
     *   ]
     *   "Alt-Svc" => [
     *     (truncated)
     *   ]
     * ]
     * ```
     *
     * Example return object:
     * ```php
     * {#51667
     *   +"ETag": ""nMRgLWac8h8NyH7Uk5VvV4DiNp4uxXg5gNUd9YhyaJE/dky_PFyA8Zq0WLn1WqUCn_A8oes""
     *   +"Content-Type": "application/json; charset=UTF-8"
     *   +"Vary": "Origin X-Origin Referer"
     *   +"Date": "Mon, 24 Jan 2022 15:39:46 GMT"
     *   +"Server": "ESF"
     *   +"Content-Length": "355675"
     *   +"X-XSS-Protection": "0"
     *   +"X-Frame-Options": "SAMEORIGIN"
     *   +"X-Content-Type-Options": "nosniff"
     *   +"Alt-Svc": (truncated)
     * }
     * ```
     *
     * @param array $header_response
     *
     * @return object
     */
    protected function convertHeadersToObject(array $header_response): object
    {
        $headers = [];

        foreach ($header_response as $header_key => $header_value) {
            // if($header_key != '')
            $headers[$header_key] = implode(" ", $header_value);
        }

        return (object) $headers;
    }

    /**
     * Convert paginated API response array into an object
     *
     * @param array $paginatedResponse Combined object returns from multiple pages of
     * API responses
     *
     * @return object Object of the API responses combined.
     */
    protected function convertPaginatedResponseToObject(
        array $paginatedResponse
    ): object {
        $results = [];

        foreach ($paginatedResponse as $response_key => $response_value) {
            $results[$response_key] = $response_value;
        }
        return (object) $results;
    }

    /**
     * Parse the API response and return custom formatted response for consistency
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
     * @see https://laravel.com/docs/8.x/http-client#making-requests
     *
     * @param object $response Response object from API results
     *
     * @param false $paginated If the response is paginated or not
     *
     * @return object Custom response returned for consistency
     */
    protected function parseApiResponse(object $response, bool $paginated = false): object
    {
        return (object) [
            'headers' => $this->convertHeadersToObject($response->headers()),
            'json' => $paginated == true ? json_encode($response->paginated_results) : json_encode($response->json()),
            'object' => $paginated == true ? (object) $response->paginated_results : $response->object(),
            'status' => (object) [
                'code' => $response->status(),
                'ok' => $response->ok(),
                'successful' => $response->successful(),
                'failed' => $response->failed(),
                'serverError' => $response->serverError(),
                'clientError' => $response->clientError(),
            ],
        ];
    }
}
