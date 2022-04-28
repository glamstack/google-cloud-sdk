<?php

return [

    /**
     * ------------------------------------------------------------------------
     * Default Configuration
     * ------------------------------------------------------------------------
     *
     * @see https://laravel.com/docs/9.x/logging
     *
     * @param string $connection
     *      The default connection key (array key) that you want to use if not
     *      specified when instantiating the ApiClient. You can add the
     *      `GOOGLE_CLOUD_DEFAULT_CONNECTION` variable in your .env file so you
     *      don't need to pass the connection key into the ApiClient. The
     *      `test_project` connection key is used `.env` variable is not set.
     *      ```php
     *      $google_cloud = new \Glamstack\GoogleCloud\ApiClient();
     *      ```
     *
     * @param array  $log_channels
     *      The Laravel log channels to send all info and error logs to for SDK
     *      connections and authentication calls. If you leave this at the value
     *      of `['single']`, all API call logs will be sent to the default log
     *      file for Laravel that you have configured in config/logging.php
     *      which is usually storage/logs/laravel.log.
     *
     *      If you would like to see Google API logs in a separate log file
     *      that is easier to triage without unrelated log messages, you can
     *      create a custom log channel and add the channel name to the
     *      array. For example, we recommend creating a custom channel
     *      (ex. `glamstack-google-cloud`), however you can choose any
     *      name you would like.
     *      Ex. ['single', 'glamstack-google-cloud']
     *
     *      You can also add additional channels that logs should be sent to.
     *      Ex. ['single', 'glamstack-google-cloud', 'slack']
     */

    'default' => [
        'connection' => env('GOOGLE_CLOUD_DEFAULT_CONNECTION', 'test'),
        'log_channels' => ['single'],
    ],

    /**
     * ------------------------------------------------------------------------
     * Service Account Connections Configuration
     * ------------------------------------------------------------------------
     *
     * Each service account is considered a "connection" and has an array key
     * that we refer to as the "connection key" that contains a array of
     * configuration values and is used when the ApiClient is instantiated.
     *
     * If you're just getting started, you can use the `test_project` connection
     * key and create a service account with `roles/editor`.
     *
     * ```php
     * $google_auth = new \Glamstack\GoogleCloud\ApiClient('test');
     * ```
     *
     * You can create one or more service accounts in your GCP project(s) with
     * different roles and permissions based on your least privilege model. You
     * can add additional connection keys for each of your service accounts
     * using a snake case name of your choosing.
     *
     * @param string $project_id
     *      The GCP project ID associated with the GCP service account key.
     *      If the service account has organization-level permissions, this
     *      is the GCP project that the service account was created in.
     *      This can be found inside of the JSON file contents and may be
     *      a 12-digit integer or an alphadash name.
     *
     *      ```
     *      123456789012
     *      my-project-a1b2c3
     *      ```
     *
     * @param array $api_scopes
     *      The API OAUTH scopes that will be needed for the GCP API endpoints
     *      that will be used. These need to match what you have granted your
     *      service account.
     *
     *      https://developers.google.com/identity/protocols/oauth2/scopes
     *
     *      ```php
     *      [
     *          'https://www.googleapis.com/auth/admin.directory.user',
     *          'https://www.googleapis.com/auth/cloud-platform',
     *          'https://www.googleapis.com/auth/compute',
     *          'https://www.googleapis.com/auth/ndev.clouddns.readwrite'
     *          'https://www.googleapis.com/auth/cloud-billing',
     *          'https://www.googleapis.com/auth/monitoring.read',
     *      ]
     *      ```
     *
     * @param ?string $json_key_file
     *      You can specify the full operating system path to the JSON key file.
     *
     *      If null, the GCP service account JSON API key file that you
     *      generate and download should be added to your locally cloned
     *      repository in the `storage/keys/google-cloud-sdk` directory with
     *      the filename that matches the connection key.
     *
     *      ```php
     *      storage('keys/google-cloud-sdk/test.json')
     *      ```
     *
     * @param array  $log_channels
     *      The Laravel log channels to send all related info and error
     *      logs to. If you leave this at the value of `['single']`, all
     *      API call logs will be sent to the default log file for Laravel
     *      that you have configured in `config/logging.php` which logs to
     *      `storage/logs/laravel.log`.
     *
     *      If you would like to see Google API logs in a separate log file
     *      that is easier to triage without unrelated log messages, you can
     *      create a custom log channel and add the channel name to the
     *      array. For example, we recommend creating a custom channel
     *      (ex. `glamstack-google-cloud`), however you can choose any
     *      name you would like.
     *      ```php
     *      ['single', 'glamstack-google-cloud']
     *      ```
     *
     *      You can also add additional channels that logs should be sent to.
     *      ```php
     *      ['single', 'glamstack-google-cloud', 'slack']
     *      ```
     */

    'connections' => [

        'test' => [
            'project_id' => env('GOOGLE_CLOUD_TEST_PROJECT_ID'),
            'api_scopes' => [
                'https://www.googleapis.com/auth/cloud-platform',
                'https://www.googleapis.com/auth/compute',
            ],
            'email' => env('GOOGLE_WORKSPACE_USER_EMAIL'),
            'json_key_file' => null,
            'log_channels' => ['single']
        ],

    ]
];
