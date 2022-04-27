<?php

namespace Glamstack\GoogleCloud;

use Glamstack\GoogleCloud\Resources\Dns\Dns;

class ApiClient
{
    // Standard parameters for building the GoogleDriveApiClient
    private string $config_path = 'glamstack-google.';
    public ?string $connection_key;
    public array $connection_config;
    public array $request_headers;

    public function __construct(
        ?string $connection_key = null,
        ?array $connection_config = []
    )
    {
        // Set the connection key used for getting the correct configuration
        if(empty($connection_config)){
            $this->setConnectionKey($connection_key);
            $this->connection_config = [];
        } else {
            $this->connection_config = $connection_config;
            $this->connection_key = null;
        }

        // Set the request headers to be used by the API client
        $this->setRequestHeaders();
    }

    public function dns(): Dns
    {
        return new Dns($this->connection_key, $this->connection_config);
    }

    /**
     * Set the connection_key class variable. The connection_key variable by default
     * will be set to `workspace`. This can be overridden when initializing the
     * SDK with a different connection key which is passed into this function to
     * set the class variable to the provided key.
     *
     * @param ?string $connection_key (Optional) The connection key to use from the
     * configuration file.
     *
     * @return void
     */
    protected function setConnectionKey(?string $connection_key): void
    {
        if($connection_key == null) {
            $this->connection_key = config(
                $this->config_path.'auth.default_connection'
            );
        } else {
            $this->connection_key = $connection_key;
        }
    }

    /**
     * Set the request headers for the GitLab API request
     *
     * @return void
     */
    protected function setRequestHeaders(): void
    {
        // Get Laravel and PHP Version
        $laravel = 'laravel/'.app()->version();
        $php = 'php/'.phpversion();

        // Decode the composer.lock file
//        $composer_lock_json = json_decode(
//            (string) file_get_contents(base_path('composer.lock')),
//            true
//        );

        // Use Laravel collection to search for the package. We will use the
        // array to get the package name (in case it changes with a fork) and
        // return the version key. For production, this will show a release
        // number. In development, this will show the branch name.
        /** @phpstan-ignore-next-line */
//        $composer_package = collect($composer_lock_json['packages'])
//            ->where('name', 'glamstack/google-cloud-dns-sdk')
//            ->first();
        /** @phpstan-ignore-next-line */
//        $package = $composer_package['name'].'/'.$composer_package['version'];
        $package = 'glamstack-google-cloud/1';
        // Define request headers
        $this->request_headers = [
            'User-Agent' => $package.' '.$laravel.' '.$php
        ];
    }
}
