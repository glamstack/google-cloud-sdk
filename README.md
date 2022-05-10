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

### Connection Keys

TODO

### Using Pre-Configured Endpoints

TODO

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

### Invalid

### Missing

### Invalid or Mismatched

## Issue Tracking and Bug Reports

## Contributing
