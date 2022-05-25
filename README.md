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

The package is not intended to provide functions for every endpoint in the Google Cloud API.

We have taken a simpler approach by providing a universal ApiClient that can perform `GET`, `POST`, `PUT`, `PATCH,` and `DELETE` requests to any endpoint that you find in the Google API documentation and handles the API response, error handling, and pagination for you.

This builds upon the simplicity of the Laravel HTTP Client that is powered by the Guzzle HTTP client to provide "last lines of code parsing" for Google API responses to improve the developer experience.

We have additional classes and methods for the endpoints that GitLab Access Manager uses frequently that we will iterate upon over time.

* [Generic REST Calls](#generic-rest-calls)
    * [GET Request](#get-request)
    * [POST Request](#post-request)
    * [PUT Request](#put-request)
    * [PATCH Request](#patch-request)
    * [DELETE Request](#delete-request)
* [Cloud DNS Endpoints](#cloud-dns-endpoints)
    * [Managed Zones](#cloud-dns---managed-zones)
        * [Get a list of Zones](#get-a-list-of-zones)
        * [Get a specific Zone](#get-a-specific-zone)
        * [Create a Zone](#create-a-zone)
        * [Delete a Zone](#delete-a-zone)
    * [Recordsets](#cloud-dns---record-sets)
        * [Get a list of Records](#get-a-list-of-records)
        * [Get a specific Record](#get-a-specific-record)
        * [Create a Record](#create-a-record)
        * [Delete a Record](#delete-a-record)

## Installation

### Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | >=8.0   |

### Add Composer Package

This package uses [Calendar Versioning](#calendar-versioning).

We recommend always using a specific version in your `composer.json` file and reviewing the [changelog](changelog/) to see the breaking changes in each release before assuming that the latest release is the right choice for your project.

```bash
composer require glamstack/google-cloud-sdk:2.5.25
```

> If you are contributing to this package, see [CONTRIBUTING](CONTRIBUTING.md) for instructions on configuring a local composer package with symlinks.

### Publish the configuration file

```bash
php artisan vendor:publish --tag=glamstack-google-cloud
```

### Version upgrades

If you have upgraded to a newer version of the package, you should back up your existing configuration file to avoid your custom configuration being overridden.

```bash
cp config/glamstack-google-cloud.php config/glamstack-google-cloud.php.bak

php artisan vendor:publish --tag=glamstack-google-cloud
```

### Calendar Versioning

The GitLab IT Engineering team uses a modified version of [Calendar Versioning (CalVer)](https://calver.org/) instead of [Semantic Versioning (SemVer)](https://semver.org/). CalVer has a YY (Ex. 2021 => 21) but having a version `21.xx` feels unintuitive to us. Since our team started this in 2021, we decided to use the last integer of the year only (2021 => 1.x, 2022 => 2.x, etc).

The version number represents the release date in `vY.M.D` format.

#### Why We Don't Use Semantic Versioning

1. We are continuously shipping to `main`/`master`/`production` and make breaking changes in most releases, so having semantic backwards-compatible version numbers is unintuitive for us.
1. We don't like to debate what to call our release/milestone and whether it's a major, minor, or patch release. We simply write code, write a changelog, and ship it on the day that it's done. The changelog publication date becomes the tagged version number (Ex. `2022-02-01` is `v2.2.1`). We may refer to a bigger version number for larger releases (Ex. `v2.2`), however this is only for monthly milestone planning and canonical purposes only. All code tags include the day of release (Ex. `v2.2.1`).
1. This allows us to automate using GitLab CI/CD to automate the version tagging process based on the date the pipeline job runs.
1. We update each of our project `composer.json` files that use this package to specific or new version numbers during scheduled change windows without worrying about differences and/or breaking changes with "staying up to date with the latest version". We don't maintain any forks or divergent branches.
1. Our packages use underlying packages in your existing Laravel application, so keeping your Laravel application version up-to-date addresses most security concerns.

## Initializing the SDK

Initialization of the API Client can be done either by passing in a (string) [connection_key](#connection-keys) or by passing in an (array) [connection_config](#dynamic-connection-config-array)

### Google API Authentication

The package utilizes the [glamstack/google-auth-sdk](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-auth-sdk) package for creating the [Google JWT Web Token](https://cloud.google.com/iot/docs/how-tos/credentials/jwts) to authenticate with [Google Cloud API endpoints](https://cloud.google.com/apis).

For more information on [glamstack/google-auth-sdk](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-auth-sdk) please see the [Google Auth SDK README.md](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-auth-sdk/-/blob/main/README.md).

### Connection Keys

We use the concept of **_connection keys_** that refer to a configuration array in `config/glamstack-google-cloud.php` that allows you to pre-configure one or more API connections.

Each connection key is associated with a GCP service account JSON key. This can be used to configure different auth scope connections and permissions to your GCP organization or different GCP project(s) depending on the API calls that you're using. This allows for least privilege for specific API calls, and you can also configure multiple connections with the same GCP project and different API tokens that have different permission levels.

#### Example Connection Key Initialization

```php
// Initialize the SDK using the `test` configuration from `glamstack-google-cloud.php`
$client = new Glamstack\GoogleCloud\ApiClient('test');
```

#### Example Connection Key Configuration

```php
return [
    'connections' => [
        'test' => [
            'project_id' => env('GOOGLE_CLOUD_TEST_PROJECT_ID'),
            'api_scopes' => [
                'https://www.googleapis.com/auth/ndev.clouddns.readwrite'
            ],
            'json_key_file' => storage_path('keys/glamstack-google-cloud/test.json'),
            'log_channels' => ['single']
        ]
    ]
]
```

### Dynamic Connection Config Array

If you don't want to pre-configure your connection and prefer to dynamically use connection variables that are stored in your database, you have the ability to pass in the required configurations via an array (See [Example Connection Config Array Initialization](#example-connection-config-array-initialization)) using the `connection_config` array in the second argument of the `ApiClient` construct method.

#### Required Parameters

| Key                  | Type   | Description |
|----------------------|--------|-------------|
| `api_scopes`         | array  | Array of the API Scopes needed for the APIs to be used |
| `project_id`         | string | The Google Project ID to run the API call on |
| `json_key_file_path` | string | Option 1 - Provide a file path to the `.json` key file |
| `json_key`           | string | Option 2 - Provide the JSON key contents stored in your database |

#### Using a JSON Key File on your filesystem

```php
$client = new Glamstack\GoogleCloud\ApiClient(null, [
    'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
    'json_key_file_path' => storage('keys/glamstack-google-cloud/gcp_project_1.json'),
    'project_id' => 'example-project-123'
]);
```

#### Using a JSON Key String in your database

**Security Warning:** You should never commit your service account key (JSON contents) into your source code as a variable to avoid compromising your credentials for your GCP organization or projects.

It is recommended to convert the JSON key to a base 64 encoded string before encryption since this is the format used by the GCP Service Account API for the `privateKeyData` field.

```php
// Get service account from your model (`GoogleServiceAccount` is an example)
$service_account = \App\Models\GoogleServiceAccount::where('id', '123456')->firstOrFail();

// Get JSON key string from database column that has an encrypted value
$json_key_string = decrypt(json_decode($service_account->json_key));

$client = new \Glamstack\GoogleCloud\ApiClient(null, [
    'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
    'json_key' => $json_key_string,
    'project_id' => 'example-project-123'
]);
```

The example below shows the value of the JSON key that is stored in your database.

```php
// Get service account from your model (`GoogleServiceAccount` is an example)
$service_account = \App\Models\GoogleServiceAccount::where('id', '123456')->firstOrFail();

dd(decrypt(json_decode($service_account->json_key));
// {
//     "type": "service_account",
//     "project_id": "project_id",
//     "private_key_id": "key_id",
//     "private_key": "key_data",
//     "client_email": "xxxxx@xxxxx.iam.gserviceaccount.com",
//     "client_id": "123455667897654",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
//     "client_x509_cert_url": "some stuff"
// }
```

## Endpoints

### Generic REST Calls

You need to initialize the API client and set the project ID for every endpoint that you use.

> The `$project_id` variable is defined for documentation and education purposes. You can also embed the project ID in the URL instead of defining a variable.

```php
$client = new Glamstack\GoogleCloud\ApiClient('test');
$project_id = config('glamstack-google-cloud.connections.test.project_id');
```

#### GET Request

> Example: https://cloud.google.com/compute/docs/reference/rest/v1/addresses/list

```php
$response = $client->rest()->get('https://compute.googleapis.com/compute/v1/projects/' . $project_id . '/regions/us-central1/addresses', []);
```

> Example: https://cloud.google.com/compute/docs/reference/rest/v1/addresses/get

```php
$resource_id = 'string';

$response = $client->rest()->get('https://compute.googleapis.com/compute/v1/projects/' . $project_id . '/regions/us-central1/addresses/' . $resource_id, []);
```

#### POST Request

> Example: https://cloud.google.com/compute/docs/reference/rest/v1/addresses/insert

```php
$request_data = [
    'name' => 'string',
    'description' => 'string',
    'networkTier' => 'enum',
    'ipVersion' => 'enum',
    'addressType' => 'enum',
    'subnetwork' => 'string',
    'network' => 'string'
];

$response = $client->rest()->post('https://compute.googleapis.com/compute/v1/projects/' . $project_id . '/regions/us-central1/addresses', $request_data);
```

#### PATCH and PUT Requests

> Some PATCH requests use a [Field Mask](https://google.aip.dev/161) to provide a list of fields to be updated. This may need to be added to the `$request_data` array depending on the API endpoint documentation.

```php
$resource_id = 'string';

// This is a different style of using variables that may be helpful for some
// resources that have a full URI returned with the `POST` method
$resource_uri = 'projects/' . $project_id . '/path/to/endpoint/' . $resource_id;

$request_data = [
    'description' => 'string',
];
```

```php
$response = $client->rest()->patch('https://compute.googleapis.com/compute/v1/' . $resource_uri, $request_data);
```

```php
$response = $client->rest()->put('https://compute.googleapis.com/compute/v1/' . $resource_uri, $request_data);
```

#### DELETE Request

> Example: https://cloud.google.com/compute/docs/reference/rest/v1/addresses/delete

```php
$response = $client->rest()->delete('https://compute.googleapis.com/compute/v1/projects/' . config('glamstack-google-cloud.connections.test.project_id') . '/regions/us-central1/addresses/{resourceId}');
```

### Cloud DNS - Managed Zones

See the [API documentation](https://cloud.google.com/dns/docs/reference/v1/managedZones) to learn more.

```php
$client = new Glamstack\GoogleCloud\ApiClient('test');
```

#### Get a list of Zones

```php
$response = $client->dns()->managedZone()->list();
```

#### Get a specific Zone

```php
$response = $client->dns()->managedZone()->get('testing-zone');
```

#### Create a Zone

```php
$response = $client->dns()->managedZone()->create([
    'name' => 'testing-zone-3',
    'dns_name' => 'testing-zone-3.example.com.',
    'visibility' => 'private',
    'dnssec_config_state' => 'off',
    'description' => 'Testing zone 3 by SDK',
]);
```

#### Delete a Zone

```php
$response = $client->dns()->managedZone()->delete('testing-zone-3');
```

### Cloud DNS - Record Sets

See the [API documentation](https://cloud.google.com/dns/docs/reference/v1/resourceRecordSets) to learn more.

```php
$client = new Glamstack\GoogleCloud\ApiClient('test');
```

#### Get a List of Records

```php
$response = $client->dns()->recordSet()->list('testing-zone');
```

#### Get a specific Record

```php
$response = $client->dns()->recordSet()->get(
    'testing-zone',
    'testingmail.testingzone.example.com.',
    'CNAME'
);
```

#### Create a Record

```php
$response = $client->dns()->recordSet()->create('testing-zone', [
    'name' => 'testingmail.testingzone.example.com.',
    'type' => 'CNAME',
    'ttl' => 300,
    'rrdatas' => ['mail.testingzone.example.com.']
    ]
);
```

#### Delete a Record

```php
$response = $client->dns()->RecordSet()->delete(
    'testing-zone',
    'testingmail.testingzone.example.com.',
    'CNAME'
);
```

## Logging Configuration

By default, we use the `single` channel for all logs that is configured in your application's `config/logging.php` file. This sends all Google Cloud log messages to the `storage/logs/laravel.log` file.

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
            'channels' => ['single','slack','glamstack-google-cloud'],
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

This SDK has feature and unit tests written with the [Pest](https://pestphp.com/) framework.

### Running The Test

You can run all tests in the SDK from the project directory.

```bash
cd ~/Sites/google-cloud-sdk
./vendor/bin/pest
```

Alternatively, you can utilize build in composer commands to run the test.

```bash
composer test
```

You can also run the tests with a coverage report.

```bash
composer test-coverage
```

## Issue Tracking and Bug Reports

Please visit our [issue tracker](https://gitlab.com/gitlab-com/business-technology/engineering/access-manager/packages/composer/google-cloud-sdk/-/issues) and create an issue or comment on an existing issue.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) to learn more about how to contribute.
