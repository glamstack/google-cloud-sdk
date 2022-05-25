<?php

namespace Glamstack\GoogleCloud\Tests\Fakes;

use Glamstack\GoogleCloud\Resources\BaseClient;

class BaseClientFake extends BaseClient
{
    public function parseConfigFile(string $connection_key): array
    {
        return parent::parseConfigFile($connection_key);
    }

    public function getConfigApiScopes(string $connection_key)
    {
        return parent::getConfigApiScopes($connection_key);
    }

    public function getConfigSubjectEmail(string $connection_key)
    {
        return parent::getConfigSubjectEmail($connection_key);
    }

    public function getConfigJsonFilePath(string $connection_key)
    {
        return parent::getConfigJsonFilePath($connection_key);
    }

    public function parseConnectionConfigArray(array $connection_config)
    {
        return parent::parseConnectionConfigArray($connection_config);
    }

    public function getConfigArrayApiScopes(array $connection_config)
    {
        return parent::getConfigArrayApiScopes($connection_config);
    }

    public function getConfigArraySubjectEmail(array $connection_config)
    {
        return parent::getConfigArraySubjectEmail($connection_config);
    }

    public function getConfigArrayFilePath(array $connection_config)
    {
        return parent::getConfigArrayFilePath($connection_config);
    }

    public function getConfigArrayJsonKey(array $connection_config)
    {
        return parent::getConfigArrayJsonKey($connection_config);
    }
}
