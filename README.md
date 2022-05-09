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

This package has two different utilization's:
1. [Using the Pre-Configured Resources](#using-pre-configured-endpoints):
    * DNS
       * ManagedZone Resource
       * RecordSet Resource
2. [Using the REST Resource](#custom-non-configured-connections):
    * This contains the standard `GET`, `POST`, `PUT`, `PATCH`, and `DELETE` API calls
    * To use this resource you will be required to pass in the full endpoint `url` to the method
    > **Note:** These methods are only tested to the point that we know they will run the proper HTTP Methods when utilized. There is no parameter validation

## Installation

### Requirements

### Add Composer Package

### Publish Configuration

### Version Upgrades

### Related SDK Packages

### Calendar Versioning

### Connection Keys

### API Scopes

### Using Pre-Configured Connections

### Custom Non-Configured Connections

## Logging Configuration

### Creating a Log Channel

## Security Best Practices

### Google API Scopes

### JSON Key Storage

## Log Outputs

### Valid 

### Invalid

### Missing 

### Invalid or Mismatched

## Issue Tracking and Bug Reports

## Contributing