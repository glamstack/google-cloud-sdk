# google-cloud-sdk

## Overview

The Google Cloud SDK is an open source [Composer](https://getcomposer.org/) package created by [GitLab IT Engineering](https://about.gitlab.com/handbook/business-technology/engineering/) for use in the [GitLab Access Manager](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager) Laravel application for connecting to [Google Cloud API endpoints](https://cloud.google.com/apis).

> **Disclaimer:** This is not an official package maintained by the Google or GitLab product and development teams. This is an internal tool that we use in the GitLab IT department that we have open sourced as part of our company values.
>
> Please use at your own risk and create issues for any bugs that you encounter.
>
> We do not maintain a roadmap of community feature requests, however we invite you to contribute and we will gladly review your merge requests.

### Dependencies

> **Note:** This package will require the glamstack/google-auth-sdk package in order to operate. This is already configured as a required package in the composer.json file and should be automatically loaded when installing this package.

### Maintainers

| Name                                                                   | GitLab Handle                                          |
| ---------------------------------------------------------------------- | ------------------------------------------------------ |
| [Dillon Wheeler](https://about.gitlab.com/company/team/#dillonwheeler) | [@dillonwheeler](https://gitlab.com/dillonwheeler)     |
| [Jeff Martin](https://about.gitlab.com/company/team/#jeffersonmartin)  | [@jeffersonmartin](https://gitlab.com/jeffersonmartin) |

### How It Works

The package utilizes the [glamstack/google-auth-sdk](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-auth-sdk) package for creating the [Google JWT Web Token](https://cloud.google.com/iot/docs/how-tos/credentials/jwts) to authenticate with [Google Workspace API's](https://developers.google.com/admin-sdk/directory/reference/rest#service:-admin.googleapis.com).

For more information on [glamstack/google-auth-sdk](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-auth-sdk) please see the [Google Auth SDK README.md](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-auth-sdk/-/blob/main/README.md).

You can choose which approach you'd like to use to perform API calls:

1. [Using the Pre-Configured Resources](#using-pre-configured-endpoints):
    * DNS
       * ManagedZone Resource
       * RecordSet Resource
2. [Using the REST Resource](#custom-non-configured-connections):
    * This contains the standard `GET`, `POST`, `PUT`, `PATCH`, and `DELETE` API calls
    * To use this resource you will be required to pass in the full endpoint `url` to the method
    > **Note:** These methods are only tested to the point that we know they will run the proper HTTP Methods when utilized. There is no parameter validation.

## Installation

### Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | >=8.0   |

### Add Composer Package

This package uses [Calendar Versioning](#calendar-versioning).

We recommend always using a specific version in your `composer.json` file and reviewing the [changelog](changelog/) to see the breaking changes in each release before assuming that the latest release is the right choice for your project.

```bash
composer require glamstack/google-cloud-sdk:2.5.10
```

> If you are contributing to this package, see [CONTRIBUTING](CONTRIBUTING.md) for instructions on configuring a local composer package with symlinks.

### Calendar Versioning

The GitLab IT Engineering team uses a modified version of [Calendar Versioning (CalVer)](https://calver.org/) instead of [Semantic Versioning (SemVer)](https://semver.org/). CalVer has a YY (Ex. 2021 => 21) but having a version `21.xx` feels unintuitive to us. Since our team started this in 2021, we decided to use the last integer of the year only (2021 => 1.x, 2022 => 2.x, etc).

The version number represents the release date in `vY.M.D` format.

#### Why We Don't Use Semantic Versioning

1. We are continuously shipping to `main`/`master`/`production` and make breaking changes in most releases, so having semantic backwards-compatible version numbers is unintuitive for us.
1. We don't like to debate what to call our release/milestone and whether it's a major, minor, or patch release. We simply write code, write a changelog, and ship it on the day that it's done. The changelog publication date becomes the tagged version number (Ex. `2022-02-01` is `v2.2.1`). We may refer to a bigger version number for larger releases (Ex. `v2.2`), however this is only for monthly milestone planning and canonical purposes only. All code tags include the day of release (Ex. `v2.2.1`).
1. This allows us to automate using GitLab CI/CD to automate the version tagging process based on the date the pipeline job runs.
1. We update each of our project `composer.json` files that use this package to specific or new version numbers during scheduled change windows without worrying about differences and/or breaking changes with "staying up to date with the latest version". We don't maintain any forks or divergent branches.
1. Our packages use underlying packages in your existing Laravel application, so keeping your Laravel application version up-to-date addresses most security concerns.

## Usage Instructions

Initialization of this package can be done either by passing in a (string) [connection_key](#connection-keys) or by passing in a (array) [connection_config](#connection-config-array)

### Connection Keys

To utilize the `connection_key` initialization of the SDK the `glamstack-google-cloud.php` configuration file must be updated with the connection you want to utilize (See [Example Connection Key Configuration](#example-connection-key-configuration-initialization)).

After completing the `glamstack-google-cloud.php` configuration file you can utilize the connection key from the file by passing in the `connection key` name (See [Example Connection Key Initialization](#example-connection-key-initialization))

This will allow for configuring of multiple different connections to be utilized by the SDK.

#### Example Connection Key Initialization

```php
// Initialize the SDK to use `gcp_project_1` configuration from `glamstack-google-cloud.php`
$client = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
```

#### Example Connection Key Configuration Initialization

```php
return [    
    'connections' => [
        'gcp_project_1' => [
            'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'api_scopes' => [
                'https://www.googleapis.com/auth/ndev.clouddns.readwrite'
            ],
            'json_key_file' => storage_path('keys/glamstack-google-cloud/gcp_project_1.json'),
            'log_channels' => ['single']
        ]
    ]
]
```

### Connection Config Array

To utilize the `connection_config` array of the SDK you are not required to have an updated configuration in the `glamstack-google-cloud.php` configuration file. Instead, you have the ability to pass in the required configurations via an array (See [Example Connection Config Array Initialization](#example-connection-config-array-initialization)).

#### Required Parameters

1. `api_scopes` (Array)
    * Array of the API Scopes needed for the APIs to be used
2. `project_id` (String)
    * The Google Project ID to run the API call on
3. `json_key_file_path` (String) **OR** `json_key` (String)

#### Example Connection Config Array Initialization

##### Using A Stored Google JSON Auth Key

```php
$client = new Glamstack\GoogleCloud\ApiClient(null, [
    'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
    'json_key_file_path' => 'storage/keys/glamstack-google-cloud/gcp_project_1.json',
    'project_id' => 'example_project_id_123'
]);
```

##### Using The `json_key` Parameter
```php
$json_string = '{
    "type": "service_account",
    "project_id": "project_id",
    "private_key_id": "key_id",
    "private_key": "key_data",
    "client_email": "xxxxx@xxxxx.iam.gserviceaccount.com",
    "client_id": "123455667897654",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url": "some stuff"
}';

$client = new Glamstack\GoogleCloud\ApiClient(null, [
    'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
    'subject_email' => 'example@example.com',
    'json_key' => $json_string
]);
```

### Using Pre-Configured Endpoints

The pre-configured endpoints in this SDK contain input validation as and are verified via testing with [Pest](https://pestphp.com/)

#### Available Endpoints

1. [Cloud DNS](https://cloud.google.com/dns/docs/reference/v1)
   1. [ManagedZones](https://cloud.google.com/dns/docs/reference/v1/managedZones)
   2. [RecordSets](https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets)

#### Example Inline Usage

```php
$client = new Glamstack\GoogleCloud\ApiClient('test');
$response = $client->dns()->managedZone()->get('testing-zone');
```

### Custom Non-Configured Connections

Due to the time constraints this SDK also has the ability to create HTTP calls to any Google Cloud API endpoint utilizing the Rest resource. The Rest resource will allow you to input any Google Cloud API endpoint as the uri and it will run the method you called on that endpoint.

#### Example Inline Usage

```php
$client = new Glamstack\GoogleCloud\ApiClient('test');
$response = $client->rest()->get('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones', []);
```

## Logging Configuration

By default, we use the `single` channel for all logs that is configured in your application's `config/logging.php` file. This sends all Google Workspace log messages to the `storage/logs/laravel.log` file.

If you would like to see Google Cloud logs in a separate log file that is easier to triage without unrelated log messages, you can create a custom log channel.  For example, we recommend using the value of `glamstack-google-cloud`, however you can choose any name you would like.

Add the custom log channel to `config/logging.php`.

### Creating A Log Channel

```php
    'channels' => [

        // Add anywhere in the `channels` array

        'glamstack-google-cloud' => [
            'name' => 'glamstack-google-cloud',
            'driver' => 'single',
            'level' => 'debug',
            'path' => storage_path('logs/glamstack-google-cloud.log'),
        ],
    ],
```

Update the `channels.stack.channels` array to include the array key (ex.  `glamstack-google-cloud`) of your custom channel. Be sure to add `glamstack-google-cloud` to the existing array values and not replace the existing values.

```php
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single','slack', 'glamstack-google-cloud'],
            'ignore_exceptions' => false,
        ],
    ],
```

## Security Best Practices

### Google API Scopes

The default configuration file loaded with the package shows an example of the API scope configuration. Be sure to follow the [Principle of Least Privilege](https://www.cisa.gov/uscert/bsi/articles/knowledge/principles/least-privilege). All of the Google Scopes can be found [here](https://developers.google.com/identity/protocols/oauth2/scopes).

You can learn more about the Authorization Scopes required by referencing the [Google API Explorer](https://developers.google.com/apis-explorer) documentation for the specific REST endpoint.

### JSON Key Storage

Do not store your JSON key file anywhere that is not included in the `.gitignore` file. This is to avoid committing your credentials to your repository (secret leak)

It is a recommended to store a copy of each JSON API key in your preferred password manager (ex. 1Password, LastPass, etc.) and/or secrets vault (ex. HashiCorp Vault, Ansible, etc.).


## Log Outputs

### Valid

```bash
[2022-05-10 15:44:58] testing.INFO: Glamstack\GoogleCloud\Resources\BaseClient::GETREQUEST 200 https://dns.googleapis.com/dns/v1/projects/example-project/managedZones/testing-zone/rrsets/testingexample.testingzone.example.com./CNAME {"api_endpoint":"https://dns.googleapis.com/dns/v1/projects/example-project/managedZones/testing-zone/rrsets/testingexample.testingzone.example.com./CNAME","api_method":"GLAMSTACK\\GOOGLECLOUD\\RESOURCES\\BASECLIENT::GETREQUEST","class":"Glamstack\\GoogleCloud\\Resources\\BaseClient","event_type":"google-cloud-api-response-info","message":"Glamstack\\GoogleCloud\\Resources\\BaseClient::GETREQUEST 200 https://dns.googleapis.com/dns/v1/projects/example-project/managedZones/testing-zone/rrsets/testingexample.testingzone.example.com./CNAME","status_code":200} 

```

### Authentication Failure

```bash
[2022-05-09 16:45:07] testing.INFO: Google OAuth2 Authentication Failed {"calling_method":"__CONSTRUCT","class":"Glamstack\\GoogleCloud\\Resources\\BaseClient","event_type":"google-auth-api-response-info","message":"Google OAuth2 Authentication Failed"} 

```

## Test Suite

This SDK has test written with the [Pest](https://pestphp.com/) Framework.

### Running The Test

To run all test in the SDK from the project directory run the following

```bash
./vendor/bin/pest
```

Alternatively you can utilize build in composer commands to run the test:

```bash
composer test
```

or

To run the test with a coverage report run the following:

```bash
composer test-coverage 
```

## Issue Tracking and Bug Reports

Please visit our [issue tracker](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-cloud-sdk/-/issues) and create an issue or comment on an existing issue.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) to learn more about how to contribute.
