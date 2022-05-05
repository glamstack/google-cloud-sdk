<?php

namespace Glamstack\GoogleCloud\Resources;

use Glamstack\GoogleCloud\ApiClient;
use Illuminate\Http\Client\Response;
use \Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Glamstack\GoogleAuth\AuthClient;

abstract class BaseClient
{

    const CONFIG_PATH = 'glamstack-google-cloud.';
    private string $auth_token;
    private string $connection_key;
    protected string $project_id;
    private array $request_headers;
    private $api_client;

    function __construct(
        ApiClient $api_client
    )
    {
        // Initialize Google Auth SDK
        $this->api_client = $api_client;

        // Set the Google Project ID
        $this->setProjectId();

        if($this->api_client->connection_key){
            $google_auth = new \Glamstack\GoogleAuth\AuthClient(
                $this->parseConfigFile($this->api_client->connection_key)
            );
        } else {
            $google_auth = new \Glamstack\GoogleAuth\AuthClient(
                $this->parseConnectionConfigArray($this->api_client->connection_config)
            );
        }

        // Authenticate with Google OAuth2 Server auth_token
        $this->auth_token = $google_auth->authenticate();
    }

    protected function parseConfigFile(string $connection_key){
        return [
            'api_scopes' => $this->getConfigApiScopes($connection_key),
            'subject_email' => $this->getConfigSubjectEmail($connection_key),
            'file_path' => $this->getConfigJsonFilePath($connection_key)
        ];
    }

    protected function getConfigApiScopes(string $connection_key){
        return config(self::CONFIG_PATH . 'connections.' . $connection_key .
            '.api_scopes');
    }

    protected function getConfigSubjectEmail(string $connection_key){
        $config_path = self::CONFIG_PATH . 'connections.' . $connection_key;
        if(array_key_exists('subject_email', config($config_path))){
            return config($config_path . '.subject_email');
        } else {
            return null;
        }
    }

    protected function getConfigJsonFilePath(string $connection_key){
        return config(self::CONFIG_PATH . 'connections.' . $connection_key .
            '.json_key_file');
    }

    protected function parseConnectionConfigArray(array $connection_config){
        return [
            'api_scopes' => $this->getConfigArrayApiScopes($connection_config),
            'subject_email' => $this->getConfigArraySubjectEmail($connection_config),
            'file_path' => $this->getConfigArrayFilePath($connection_config),
            'json_key' => $this->getConfigArrayJsonKey($connection_config)
        ];
    }

    protected function getConfigArrayApiScopes(array $connection_config){
        return $connection_config['api_scopes'];
    }

    protected function getConfigArraySubjectEmail(array $connection_config){
        if(array_key_exists('subject_email', $connection_config)){
            return $connection_config['subject_email'];
        } else {
            return null;
        }
    }

    protected function getConfigArrayFilePath(array $connection_config){
        if(array_key_exists('file_path', $connection_config)){
            return $connection_config['file_path'];
        } else {
            return null;
        }
    }

    protected function getConfigArrayJsonKey(array $connection_config){
        if(array_key_exists('json_key', $connection_config)){
            return $connection_config['json_key'];
        } else {
            return null;
        }
    }

    protected function setProjectId(){
        if($this->api_client->connection_key){
            $this->project_id = config(
                self::CONFIG_PATH . 'connections.' .
                $this->api_client->connection_key . '.project_id'
            );
        } else {
            $this->project_id = $this->api_client->connection_config['project_id'];
        }
    }
//    protected function setAuthToken(){
//        $this->auth_token = $this->api_client->auth_token;
//    }

    /**
     * Google API GET Request
     *
     * Example Usage:
     * ```php
     * $google_workspace_api = new \Glamstack\GoogleWorkspace\GoogleDriveApiClient();
     * $user_key = 'klibby@example.com`;
     * $google_workspace_api->get('/users/'.$user_key);
     * ```
     *
     * Example Response:
     * ```php
     * {#1268
     *   +"headers": {#1216
     *     +"ETag": (truncated)
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "Origin X-Origin Referer"
     *     +"Date": "Mon, 24 Jan 2022 17:25:15 GMT"
     *     +"Server": "ESF"
     *     +"Content-Length": "1259"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *   }
     *   +"json": (truncated)
     *   +"object": {#1251
     *     +"kind": "admin#directory#user"
     *     +"id": "114522752583947996869"
     *     +"etag": (truncated)
     *     +"primaryEmail": "klibby@example.com"
     *     +"name": {#1248
     *       +"givenName": "Kate"
     *       +"familyName": "Libby"
     *       +"fullName": "Kate Libby"
     *     }
     *     +"isAdmin": true
     *     +"isDelegatedAdmin": false
     *     +"lastLoginTime": "2022-01-21T17:44:13.000Z"
     *     +"creationTime": "2021-12-08T13:15:43.000Z"
     *     +"agreedToTerms": true
     *     +"suspended": false
     *     +"archived": false
     *     +"changePasswordAtNextLogin": false
     *     +"ipWhitelisted": false
     *     +"emails": array:3 [
     *       0 => {#1260
     *         +"address": "klibby@example.com"
     *         +"type": "work"
     *       }
     *       1 => {#1259
     *         +"address": "klibby@example-test.com"
     *         +"primary": true
     *       }
     *       2 => {#1255
     *         +"address": "klibby@example.com.test-google-a.com"
     *       }
     *     ]
     *     +"phones": array:1 [
     *       0 => {#1214
     *         +"value": "5555555555"
     *         +"type": "work"
     *       }
     *     ]
     *     +"languages": array:1 [
     *       0 => {#1271
     *         +"languageCode": "en"
     *         +"preference": "preferred"
     *       }
     *     ]
     *     +"nonEditableAliases": array:1 [
     *       0 => "klibby@example.com.test-google-a.com"
     *     ]
     *     +"customerId": "C000nnnnn"
     *     +"orgUnitPath": "/"
     *     +"isMailboxSetup": true
     *     +"isEnrolledIn2Sv": false
     *     +"isEnforcedIn2Sv": false
     *     +"includeInGlobalAddressList": true
     *   }
     *   +"status": {#1269
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
     * @param string $uri The URI of the Google Workspace API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Workspace API GET request
     *
     * @return object|string
     */
    public function getRequest(string $uri, array $request_data = []): object|string
    {
        // Append the Google Domain and Google Customer ID to the request data
//        $request_data = array_merge($request_data, $this->required_parameters);

        $response = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->get($uri, $request_data);

        // Check if the data is paginated
        $isPaginated = $this->checkForPagination($response);

        if($isPaginated) {

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

        return $parsed_api_response;
    }

    /**
     * Google Workspace API POST Request. Google will utilize POST request for
     * inserting a new resource.
     *
     * This method is called from other services to perform a POST request and
     * return a structured object.
     *
     * Example Usage:
     * ```php
     * $google_workspace_api = new \Glamstack\GoogleWorkspace\GoogleDriveApiClient();
     * $google_workspace_api->post('/users/',
     *     [
     *         'name' => [
     *             'givenName' => 'Kate',
     *             'familyName' => 'Libby'
     *         ],
     *         'password' => 'ac!dBurnM3ss3sWithTheB4$t',
     *         'primaryEmail' => 'klibby@example.com'
     *     ]
     * );
     * ```
     *
     * Example Response:
     * ```php
     * {#1214
     *   +"headers": {#1233
     *     +"ETag": (truncated)
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "Origin X-Origin Referer"
     *     +"Date": "Mon, 24 Jan 2022 17:35:55 GMT"
     *     +"Server": "ESF"
     *     +"Content-Length": "443"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *   }
     *   +"json": (truncated)
     *   +"object": {#1231
     *     +"kind": "admin#directory#user"
     *     +"id": "115712261629077226469"
     *     +"etag": (truncated)
     *     +"primaryEmail": "klibby@example.com"
     *     +"name": {#1255
     *       +"givenName": "Kate"
     *       +"familyName": "Libby"
     *     }
     *     +"isAdmin": false
     *     +"isDelegatedAdmin": false
     *     +"creationTime": "2022-01-24T17:35:54.000Z"
     *     +"customerId": "C000nnnnn"
     *     +"orgUnitPath": "/"
     *     +"isMailboxSetup": false
     *   }
     *   +"status": {#1260
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
     * @param string $uri The URI of the Google Workspace API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Workspace API POST request
     *
     * @return object|string
     */
    public function postRequest(string $uri, array $request_data = []): object|string
    {
        // Append to Google Domain and Google Customer ID to the request data
        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->post($uri, $request_data);
        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        return $response;
    }

    public function patchRequest(string $uri, array $request_data = []): object|string
    {
        // Append to Google Domain and Google Customer ID to the request data
        $request_data = array_merge($request_data, $this->required_parameters);
        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->api_client->request_headers)
            ->patch($uri, $request_data);
        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        return $response;
    }

    /**
     * Google Workspace API PUT Request. Google will utilize PUT request for
     * updating an existing resource.
     *
     * This method is called from other services to perform a PUT request and
     * return a structured object
     *
     * Example Usage:
     * ```php
     * $google_workspace_api = new \Glamstack\GoogleWorkspace\GoogleDriveApiClient();
     * $user_key = 'klibby@example.com';
     * $google_workspace_api->put('/users/' . $user_key,
     *     [
     *         name => [
     * .            'familyName' => 'Libby-Murphy'
     *         ]
     *     ]
     * );
     * ```
     *
     * Example Response:
     * ```php
     *    {#1271
     *   +"headers": {#1224
     *     +"ETag": (truncated)
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "Origin X-Origin Referer"
     *     +"Date": "Mon, 24 Jan 2022 17:45:47 GMT"
     *     +"Server": "ESF"
     *     +"Content-Length": "917"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *   }
     *   +"json": (truncated)
     *   +"object": {#1222
     *     +"kind": "admin#directory#user"
     *     +"id": "115712261629077226469"
     *     +"etag": (truncated)
     *     +"primaryEmail": "klibby@example.com"
     *     +"name": {#1255
     *       +"familyName": "Libby-Murphy"
     *     }
     *     +"isAdmin": false
     *     +"isDelegatedAdmin": false
     *     +"lastLoginTime": "1970-01-01T00:00:00.000Z"
     *     +"creationTime": "2022-01-24T17:35:54.000Z"
     *     +"agreedToTerms": false
     *     +"suspended": false
     *     +"archived": false
     *     +"changePasswordAtNextLogin": false
     *     +"ipWhitelisted": false
     *     +"emails": array:2 [
     *       0 => {#1260
     *         +"address": "klibby@example.com"
     *         +"primary": true
     *       }
     *       1 => {#1248
     *         +"address": "klibby@example.com.test-google-a.com"
     *       }
     *     ]
     *     +"nonEditableAliases": array:1 [
     *       0 => "klibby@example.com.test-google-a.com"
     *     ]
     *     +"customerId": "C000nnnnn"
     *     +"orgUnitPath": "/"
     *     +"isMailboxSetup": false
     *     +"includeInGlobalAddressList": true
     *     +"recoveryEmail": ""
     *   }
     *   +"status": {#1251
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
     * @param string $uri The URI of the Google Workspace API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Workspace API PUT request
     *
     * @return object|string
     */
    public function putRequest(string $uri, array $request_data = []): object|string
    {
        // Append to Google Domain and Google Customer ID to the request data
        $request_data = array_merge($request_data, $this->required_parameters);

        $request = Http::withToken($this->auth_token)
            ->withHeaders($this->request_headers)
            ->put($uri, $request_data);

        // Parse the API request's response and return a Glamstack standardized
        // response
        $response = $this->parseApiResponse($request);

        return $response;
    }

    /**
     * Google Workspace API DELETE Request. Google will utilize DELETE request
     * for removing an existing resource from the workspace.
     *
     * This method is called from other services to perform a DELETE request
     * and return a structured object.
     *
     * Example Usage:
     * ```php
     * $google_workspace_api = new \Glamstack\GoogleWorkspace\GoogleDriveApiClient();
     * $user_key = 'klibby@example.com';
     * $google_workspace_api->delete('/users/' . $user_key);
     * ```
     *
     * Example Response:
     * ```php
     * {#1255
     *   +"headers": {#1216
     *     +"ETag": (truncated)
     *     +"Vary": "Origin X-Origin Referer"
     *     +"Date": "Mon, 24 Jan 2022 17:50:04 GMT"
     *     +"Content-Type": "text/html"
     *     +"Server": "ESF"
     *     +"Content-Length": "0"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *   }
     *   +"json": "null"
     *   +"object": null
     *   +"status": {#1214
     *     +"code": 204
     *     +"ok": false
     *     +"successful": true
     *     +"failed": false
     *     +"serverError": false
     *     +"clientError": false
     *   }
     * }
     * ```
     *
     * @param string $uri The URI of the Google Workspace API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data (Optional) Optional request data to send with
     * the Google Workspace API DELETE request
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

        return $response;
    }

    /**
     * Check if pagination is used in the Google Workspace GET response.
     *
     * @param Response $response API response from Google Workspace GET request
     *
     * @return bool True if pagination is required | False if not
     * @see GOOGLE PAGINATION EXAMPLE
     *
     */
    protected function checkForPagination(Response $response): bool
    {
        // Check if Google Workspace GET Request object contains `nextPageToken`
        if(property_exists($response->object(), 'nextPageToken')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Helper method for getting Google Workspace GET responses that require
     * pagination
     *
     * @param string $uri The URI of the Google Workspace API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data Request data to send with the Google
     * Workspace API GET request
     *
     * @param Response $response API response from Google Workspace GET request
     *
     * @return array
     */
    protected function getPaginatedResults(
        string $uri,
        array $request_data,
        Response $response
    ): array
    {
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
        if($next_page_exists) {
            $next_page_token = $this->getNextPageToken($next_response);
        } else {
            $next_page_token = null;
        }

        // FIXME: Evaluate a do/while refactor based on okta-sdk

        // If there is an additional (ex. third) page then continue through all
        // data until the API response does not contain the `nextPageToken`
        // element in the returned object
        if($next_page_token) {
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
            if($next_page_exists) {
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
     * @param Response $response Google Workspace API GET Request Guzzle
     * response
     *
     * @return string
     */
    protected function getNextPageToken(Response $response): string
    {
        return $response->object()->nextPageToken;
    }

    /**
     * Helper function to get the next page of a Google Workspace API GET
     * request.
     *
     * @param string $uri The URI of the Google Workspace API request with
     * a leading slash after `https://admin.googleapis.com/admin/directory/v1`
     *
     * @param array $request_data Request data to send with the Google
     * Workspace API GET request.
     *
     * @param Response $response API response from Google Workspace GET request
     *
     * @return Response
     */
    protected function getNextPageResults(
        string $uri,
        array $request_data,
        Response $response
    ): Response
    {

        // Set the Google Workspace Query parameter `pageToken` to the
        // responses `nextPageToken` element
        $next_page = [
            'pageToken' => $this->getNextPageToken($response)
        ];

        // Merge the `request_data` with the `next_page` this tells Google
        // Workspace that we are working with a paginated response
        $request_body = array_merge($request_data, $next_page);

        $records = Http::withToken($this->auth_token)
            ->withHeaders($this->request_headers)
            ->get($uri, $request_body);

        return $records;
    }

    /**
     * Helper method to get just the response data from the Response object
     *
     * @param Response $response API response from Google Workspace GET request
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
        if($contains_next_page) {
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

        foreach($header_response as $header_key => $header_value) {
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
    ): object
    {
        $results = [];

        foreach($paginatedResponse as $response_key => $response_value) {
            $results[$response_key] = $response_value;
        }
        return (object) $results;
    }

    /**
     * Parse the API response and return custom formatted response for consistency
     *
     * Example Response:
     * ```php
     * {#1268
     *   +"headers": {#1216
     *     +"ETag": (truncated)
     *     +"Content-Type": "application/json; charset=UTF-8"
     *     +"Vary": "Origin X-Origin Referer"
     *     +"Date": "Mon, 24 Jan 2022 17:25:15 GMT"
     *     +"Server": "ESF"
     *     +"Content-Length": "1259"
     *     +"X-XSS-Protection": "0"
     *     +"X-Frame-Options": "SAMEORIGIN"
     *     +"X-Content-Type-Options": "nosniff"
     *     +"Alt-Svc": (truncated)
     *   }
     *   +"json": (truncated)
     *   +"object": {#1251
     *     +"kind": "admin#directory#user"
     *     +"id": "114522752583947996869"
     *     +"etag": (truncated)
     *     +"primaryEmail": "klibby@example.com"
     *     +"name": {#1248
     *       +"givenName": "Kate"
     *       +"familyName": "Libby"
     *       +"fullName": "Kate Libby"
     *     }
     *     +"isAdmin": true
     *     +"isDelegatedAdmin": false
     *     +"lastLoginTime": "2022-01-21T17:44:13.000Z"
     *     +"creationTime": "2021-12-08T13:15:43.000Z"
     *     +"agreedToTerms": true
     *     +"suspended": false
     *     +"archived": false
     *     +"changePasswordAtNextLogin": false
     *     +"ipWhitelisted": false
     *     +"emails": array:3 [
     *       0 => {#1260
     *         +"address": "klibby@example.com"
     *         +"type": "work"
     *       }
     *       1 => {#1259
     *         +"address": "klibby@example-test.com"
     *         +"primary": true
     *       }
     *       2 => {#1255
     *         +"address": "klibby@example.com.test-google-a.com"
     *       }
     *     ]
     *     +"phones": array:1 [
     *       0 => {#1214
     *         +"value": "5555555555"
     *         +"type": "work"
     *       }
     *     ]
     *     +"languages": array:1 [
     *       0 => {#1271
     *         +"languageCode": "en"
     *         +"preference": "preferred"
     *       }
     *     ]
     *     +"nonEditableAliases": array:1 [
     *       0 => "klibby@example.com.test-google-a.com"
     *     ]
     *     +"customerId": "C000nnnnn"
     *     +"orgUnitPath": "/"
     *     +"isMailboxSetup": true
     *     +"isEnrolledIn2Sv": false
     *     +"isEnforcedIn2Sv": false
     *     +"includeInGlobalAddressList": true
     *   }
     *   +"status": {#1269
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

    /**
     * Create an info log entry for an API call
     *
     * @param string $method The lowercase name of the method that calls this function (ex. `get`)
     *
     * @param string $endpoint The URL of the API call including the concatenated base URL and URI
     *
     * @param string $status_code The HTTP response status code (ex. `200`)
     *
     * @return void
     */
    public function logInfo(string $method, string $endpoint, string $status_code): void
    {
        $message = Str::upper($method).' '.$status_code.' '.$endpoint;

        Log::stack((array) config(self::CONFIG_PATH . 'log_channels'))
            ->info($message, [
                'event_type' => 'google-workspace-api-response-info',
                'class' => get_class(),
                'status_code' => $status_code,
                'message' => $message,
                'api_method' => Str::upper($method),
                'api_endpoint' => $endpoint,
                'google_connection' => $this->connection_key,
                'google_domain' => $this->google_domain,
                'google_customer_id' => $this->customer_id
            ]);
    }

    /**
     * Handle Google Workspace API Exception
     *
     * @param RequestException $exception An instance of the exception
     *
     * @param string $log_class get_class()
     *
     * @param string $reference Reference slug or identifier
     *
     * @return string Error message
     */
    public function handleException(
        RequestException $exception,
        string $log_class,
        string $reference
    ): string
    {
        Log::stack((array) config(self::CONFIG_PATH . 'log_channels'))
            ->error($exception->getMessage(), [
                'event_type' => 'google-workspace-api-response-error',
                'class' => $log_class,
                'status_code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'reference' => $reference,
                'gitlab_instance' => $this->instance_key,
                'gitlab_version' => $this->gitlab_version,
            ]);

        return $exception->getMessage();
    }
}
