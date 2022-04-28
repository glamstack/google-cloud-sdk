<?php

namespace Glamstack\GoogleCloud;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ApiClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('google-cloud-sdk')
            ->hasConfigFile('glamstack-google-cloud');
        // ->hasCommand(ApiClientCommand::class);
    }
}
