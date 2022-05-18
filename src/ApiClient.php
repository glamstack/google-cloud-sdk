<?php

namespace Glamstack\GoogleCloud;

use Glamstack\GoogleCloud\Models\ApiClientModel;
use Glamstack\GoogleCloud\Resources\Dns\Dns;
use Glamstack\GoogleCloud\Resources\Rest\Rest;
use Glamstack\GoogleCloud\Traits\ResponseLog;

class ApiClient
{
    use ResponseLog;

    // Standard parameters for building the GoogleDriveApiClient
    private string $config_path = 'glamstack-google-cloud.';
    public ?string $connection_key;
    public array $connection_config;
    public array $request_headers;

    /**
     * Initialize a new API Client connection using a connection key or custom
     * connection configuration array
     *
     * @param ?string $connection_key
     *      The connection key to use from `config/glamstack-google-cloud.php` file
     *      to set the appropriate Google Auth Settings.
     *
     * @param ?array $connection_config
     *      A custom array of connection configuration key/values. You can use
     *      either `json_key_array` or `json_key_file`. See the docblock in
     *      `config/glamstack-google-cloud.php` for full documentation.
     *      ```php
     *      [
     *          'project_id' => '123456789012',
     *          'auth_scopes' => [
     *              'https://www.googleapis.com/auth/cloud-platform',
     *              'https://www.googleapis.com/auth/compute'
     *          ],
     *          'json_key_array' => {json},
     *          'json_key_file' => storage('keys/glamstack-google-cloud/123456789012.json')
     *      ]
     *      ```
     *
     * @return ApiClient
     */
    public function __construct(
        ?string $connection_key = null,
        ?array $connection_config = []
    ) {
        $api_client_model = new ApiClientModel();

        // Set the connection key used for getting the correct configuration
        if (empty($connection_config)) {
            $this->setConnectionKey($connection_key);
            $this->connection_config = [];
        } else {
            $this->connection_key = null;
            $this->connection_config = $api_client_model->verifyConfigArray($connection_config);
        }

        // Set the request headers to be used by the API client
        $this->setRequestHeaders();
    }

    public function dns(): Dns
    {
        return new Dns($this->connection_key, $this->connection_config);
    }

    public function rest(): Rest
    {
        return new Rest($this->connection_key, $this->connection_config);
    }

    /**
     * Set the connection_key class variable
     *
     * If no connection_key is provided, the `GOOGLE_AUTH_DEFAULT_CONNECTION`
     * variable in `.env` is used. If the `.env` variable is not set, the value
     * is defined in `config/glamstack-google-cloud.php` and is set to `test` if
     * not defined. This can be overridden when initializing the SDK with a
     * different connection key which is passed into this function to set the
     * class variable to the provided key.
     *
     * @param ?string $connection_key (Optional)
     *      The connection key to use from the configuration file.
     *
     * @return void
     */
    protected function setConnectionKey(?string $connection_key): void
    {
        if ($connection_key == null) {
            $this->connection_key = config(
                $this->config_path.'default.connection'
            );
        } else {
            $this->connection_key = $connection_key;
        }
    }

    /**
     * Set the request headers for the Google Cloud API request
     *
     * @return void
     */
    protected function setRequestHeaders(): void
    {
        // Get Laravel and PHP Version
        $laravel = 'laravel/'.app()->version();
        $php = 'php/'.phpversion();

        // Decode the composer.lock file
        $composer_lock_json = json_decode(
            (string) file_get_contents(base_path('composer.lock')),
            true
        );

        // Use Laravel collection to search for the package. We will use the
        // array to get the package name (in case it changes with a fork) and
        // return the version key. For production, this will show a release
        // number. In development, this will show the branch name.
        /** @phpstan-ignore-next-line */
        $composer_package = collect($composer_lock_json['packages'])
            ->where('name', 'glamstack/google-cloud-sdk')
            ->first();

        /** @phpstan-ignore-next-line */
        $package = 'google-cloud-sdk'.'/dev';
        //$package = $composer_package['name'].'/'.$composer_package['version'];

        // Define request headers
        $this->request_headers = [
            'User-Agent' => $package.' '.$laravel.' '.$php
        ];
    }
}
