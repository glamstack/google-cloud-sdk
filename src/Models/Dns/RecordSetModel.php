<?php

namespace Glamstack\GoogleCloud\Models\Dns;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordSetModel
{
    private array $options;
    private OptionsResolver $resolver;

    public function __construct()
    {
        $this->resolver = new OptionsResolver();
    }

    public function verifyCreate(array $options = []): array
    {
        $this->createOptions($this->resolver);
        $this->options = $this->resolver->resolve($options);
        return $this->options;
    }

    protected function createOptions(OptionsResolver $resolver)
    {
        $resolver->define('name')
            ->required()
            ->allowedTypes('string')
            ->info('The name of the RecordSet');

        $resolver->define('ttl')
            ->required()
            ->default(300)
            ->allowedTypes('int')
            ->info('The TTL for the RecordSet');

        $resolver->define('type')
            ->required()
            ->allowedTypes('string')
            ->allowedValues('CNAME', 'A', 'AAAA')
            ->info('The DNS record type');

        $resolver->define('rrdatas')
            ->required()
            ->allowedTypes('array')
            ->info('The rrdatas for the resource');

        // Optional configurations
        $resolver->define('signature_rrdatas')
            ->allowedTypes('array')
            ->info('The signature rrdatas for the resource');

        $resolver->define('routing_policy')
            ->allowedTypes('array')
            ->info('The routing policy to assign');
    }

    public function verify(): array
    {
        return $this->options;
    }
}
