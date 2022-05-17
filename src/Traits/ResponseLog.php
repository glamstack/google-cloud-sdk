<?php

namespace Glamstack\GoogleCloud\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait ResponseLog
{
    /**
     * Create a log entry for an API call
     *
     * This method is called from other methods and will call specific methods
     * depending on the log severity level.
     *
     * @param string $url The URL of the API call including the concatenated base URL and URI
     *
     * @param object $response The HTTP response formatted with $this->parseApiResponse()
     *
     * @return void
     */
    public function logResponse(string $url, object $response) : void
    {

        $method = debug_backtrace()[1]['class'] . '::' . Str::upper(debug_backtrace()[1]['function']);

        // Status code log messages (2xx, 4xx, 5xx)
        if ($response->status->ok == true) {
            $this->logInfo($method, $url, $response);
        } elseif ($response->status->clientError == true) {
            $this->logClientError($method, $url, $response);
        } elseif ($response->status->serverError == true) {
            $this->logServerError($method, $url, $response);
        }
    }

    /**
     * Create an info log entry for an API call
     *
     * @param string $method
     *      The class and method name
     *
     * @param string $url
     *      The URL of the API call including the concatenated base URL and URI
     *
     * @param object $response
     *      The HTTP response formatted with $this->parseApiResponse()
     *
     * @return void
     */
    public function logInfo(string $method, string $url, object $response) : void
    {
        $message = $method . ' '.$response->status->code.' '.$url;

        Log::stack($this->log_channels)
            ->info($message, [
                'api_endpoint' => $url,
                'api_method' => Str::upper($method),
                'class' => get_class(),
                'event_type' => 'google-cloud-api-response-info',
                'message' => $message,
                'status_code' => $response->status->code,
            ]);
    }

    /**
     * Create a notice log entry for an API call for client errors (4xx status)
     *
     * @param string $method
     *      The class and method name
     *
     * @param string $url
     *      The URL of the API call including the concatenated base URL and URI
     *
     * @param object $response
     *      The HTTP response formatted with $this->parseApiResponse()
     *
     * @return void
     */
    public function logClientError(string $method, string $url, object $response) : void
    {
        $message = $method.' '.$response->status->code.' '.$url;

        Log::stack($this->log_channels)
            ->notice($message, [
                'api_endpoint' => $url,
                'api_method' => $method,
                'class' => get_class(),
                'event_type' => 'google-cloud-api-response-client-error',
                'google_error_type' => $response->object->error ?? null,
                'google_error_description' =>  $response->object->error_description ?? null,
                'message' => $message,
                'status_code' => $response->status->code,
            ]);
    }

    /**
     * Create an error log entry for an API call for server errors (5xx status)
     *
     * @param string $method
     *      The class and method name
     *
     * @param string $url
     *      The URL of the API call including the concatenated base URL and URI
     *
     * @param object $response
     *      The HTTP response formatted with $this->parseApiResponse()
     *
     * @return void
     */
    public function logServerError(string $method, string $url, object $response) : void
    {
        $message = $method . ' ' . $response->status->code . ' ' . $url;

        Log::stack($this->log_channels)
            ->error($message, [
                'api_endpoint' => $url,
                'api_method' => $method,
                'class' => get_class(),
                'event_type' => 'google-cloud-api-response-server-error',
                'google_error_type' => $response->object->error ?? null,
                'google_error_description' =>  $response->object->error_description ?? null,
                'message' => $message,
                'status_code' => $response->status->code,
            ]);
    }

    /**
     * Create an error log for non API related methods
     *
     * @param Exception $exception
     *      The exception thrown in the method
     *
     * @return void
     */
    public function logLocalError(Exception $exception): void
    {
        $method = debug_backtrace()[1]['function'];

        Log::stack($this->log_channels)
            ->info($exception->getMessage(), [
                'calling_method' => Str::upper($method),
                'class' => get_class(),
                'event_type' => 'google-cloud-local-sdk-error',
                'message' => $exception->getMessage(),
            ]);
    }
}
