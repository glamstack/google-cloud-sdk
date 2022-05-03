<?php


namespace Glamstack\GoogleCloud\Models\Dns;

use Symfony\Component\OptionsResolver\OptionsResolver;


class ManagedZoneModel
{

    private array $options;
    private OptionsResolver $resolver;

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
    public function verifyCreate(array $options = []): array
    {
        $this->createOptions($this->resolver);
        $this->options = $this->resolver->resolve($options);
        $this->updateOptionsArray();
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
    protected function createOptions(OptionsResolver $resolver): void
    {
        $resolver->define('name')
            ->required()
            ->allowedTypes('string')
            ->info('The name of the Managed Zone');

        $resolver->define('dns_name')
            ->required()
            ->allowedTypes('string')
            ->info('The DNS name of the managed zone');

        $resolver->define('visibility')
            ->required()
            ->allowedTypes('string')
            ->allowedValues('public', 'private')
            ->info('Rather the DNS zone is public or private');

        $resolver->define('dnssec_config_state')
            ->required()
            ->allowedTypes('string')
            ->allowedValues('on', 'off')
            ->info('The option to configure DNSSEC for the zone');

        $resolver->define('description')
            ->required()
            ->allowedTypes('string')
            ->info('The description of the DNS Zone');

        $resolver->define('cloud_logging_enabled')
            ->default(true)
            ->allowedTypes('bool')
            ->info('The option to configure logging for the zone. Defaults to true');

    }

    /**
     * Updates array properties into Google dot notation
     *
     * This method will update the `dnssec_config_state` to `dnssecConfig.state`
     * and `cloud_logging_enabled` to `cloudLoggingConfig.enableLogging`
     *
     * @return void
     */
    protected function updateOptionsArray(): void
    {
        $this->options['dnssecConfig.state'] = $this->options['dnssec_config_state'];
        unset($this->options['dnssec_config_state']);

        $this->options['cloudLoggingConfig.enableLogging'] = $this->options['cloud_logging_enabled'];
        unset($this->options['cloud_logging_enabled']);
    }
}
