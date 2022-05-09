<?php

namespace Glamstack\GoogleCloud\Models;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiClientModel
{
    private OptionsResolver $resolver;
    private array $options;

    public function __construct(){
        $this->resolver = new OptionsResolver();
    }

    /**
     * Verify required resource for creation of a managed zone
     *
     * @param array $options
     *      The request_data array for managed zone creation
     *
     * @return array
     */
    public function verifyConfigArray(array $options = []): array
    {
        $this->configArrayOption($this->resolver);
        $this->options = $this->resolver->resolve($options);
        $this->updateOptionsArray($this->options);
        return $this->options;
    }

    /**
     * Verify all required options are set
     *
     * Utilizes `OptionsResolver` for validation
     *
     * @see https://symfony.com/doc/current/components/options_resolver.html
     *
     * @param OptionsResolver $resolver
     *      The request_data array passed in for creating a managed zone
     *
     * @return void
     */
    protected function configArrayOption(OptionsResolver $resolver): void
    {
        $resolver->define('api_scopes')
            ->required()
            ->allowedTypes('array')
            ->info('The Google API Scopes to use');

        $resolver->define('project_id')
            ->required()
            ->allowedTypes('string')
            ->info('The GCP Project ID to run the API call on');

        $resolver->define('subject_email')
            ->allowedTypes('string')
            ->info('The email account to run the API call as');

        $resolver->define('file_path')
            ->allowedTypes('string')
            ->info('The storage location of the Google JSON key file');

        $resolver->define('json_key')
            ->allowedTypes('string')
            ->info('The JSON Key as a string to use for the API calls');

        $resolver->define('log_channels')
            ->allowedTypes('array')
            ->default(['single'])
            ->info('The channels to log to for the SDK');

    }

    /**
     * Updates array properties into Google dot notation
     *
     * This method will update the `dnssec_config_state` to `dnssecConfig.state`
     * and `cloud_logging_enabled` to `cloudLoggingConfig.enableLogging`
     *
     * @return void
     */
    protected function updateOptionsArray(array $options_array): void
    {
        $this->options = $options_array;
    }
}