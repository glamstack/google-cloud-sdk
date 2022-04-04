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

    public function verifyCreate(array $options = []): array{
        $this->createOptions($this->resolver);
        $this->options = $this->resolver->resolve($options);
        $this->updateOptionsArray();
        return $this->options;
    }

    protected function createOptions(OptionsResolver $resolver)
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

    protected function updateOptionsArray(){
        $this->options['dnssecConfig.state'] = $this->options['dnssec_config_state'];
        unset($this->options['dnssec_config_state']);

        $this->options['cloudLoggingConfig.enableLogging'] = $this->options['cloud_logging_enabled'];
        unset($this->options['cloud_logging_enabled']);
    }
}
