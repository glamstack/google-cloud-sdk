<?php

namespace Glamstack\GoogleCloud\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Glamstack\GoogleCloud\ApiClientServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        if(!is_dir(__DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/keys')){
            mkdir(__DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/keys');
        }
        if (!is_dir(__DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/keys/glamstack-google-cloud')){
            mkdir(__DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/keys/glamstack-google-cloud');
        }
        if (!is_link(__DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/keys/glamstack-google-cloud/test.json')) {
            symlink(__DIR__ . '/../storage/keys/glamstack-google-cloud/test.json', __DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/keys/glamstack-google-cloud/test.json');
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiClientServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
