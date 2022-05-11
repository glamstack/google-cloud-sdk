<?php

use Glamstack\GoogleCloud\Exceptions\RecordSetException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * Test of listing a managed zone's record sets
 */
it('can list recordset', function () {
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $client->dns()->recordSet()->list('testing-zone');
    expect($response->status->code)->toBe(200);
});

/**
 * Test of listing a managed zone's record set while using a custom
 * configuration during `ApiClient` initialization
 */
it('can list recordset with custom configuration', function () {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'json_key_file_path' => 'storage/keys/glamstack-google-cloud/test.json',
        'project_id' => env('GOOGLE_CLOUD_TEST_PROJECT_ID')
    ]);
    $response = $client->dns()->recordSet()->list('testing-zone');
    expect($response->status->code)->toBe(200);
});

/**
 * Test error is thrown when require parameters are missing from the create method
 */
it('does not meet the record requirements', function () {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'subject_email' => env('GOOGLE_CLOUD_TEST_SUBJECT_EMAIL'),
        'json_key_file_path' => 'storage/keys/glamstack-google-cloud/test.json',
        'project_id' => env('GOOGLE_CLOUD_TEST_PROJECT_ID')
    ]);
    $client->dns()->recordSet()->create('testing-zone', [
        'testing' => 'this should not work'
    ]);
})->throws(UndefinedOptionsException::class);

/**
 * Test error is thrown when rrdatas has incorrect value type
 */
it('does not have the required type for rrdatas', function() {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'subject_email' => env('GOOGLE_CLOUD_TEST_SUBJECT_EMAIL'),
        'json_key_file_path' => 'storage/keys/glamstack-google-cloud/test.json',
        'project_id' => env('GOOGLE_CLOUD_TEST_PROJECT_ID')
    ]);
    $client->dns()->recordSet()->create('testing-zone', [
        'name' => 'TestingSet',
        'ttl' => 300,
        'type' => 'CNAME',
        'rrdatas' => 'mail.testingzone.example.com.'
    ]);
})->throws(InvalidOptionsException::class);

/**
 * Test the creation of a record set
 */
it('can create a recordSet', function() {
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $client->dns()->recordSet()->create('testing-zone', [
        'name' => 'testingmail.testingzone.example.com.',
        'type' => 'CNAME',
        'ttl' => 300,
        'rrdatas' => ['mail.testingzone.example.com.']
        ]
    );
    expect($response->status->code)->toBe(200);
});

/**
 * Test getting a specific record set
 */
it('can get a specific record set', function(){
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $client->dns()->recordSet()->get(
        'testing-zone',
        'testingmail.testingzone.example.com.',
        'CNAME'
    );
    expect($response->object->name)->toBe('testingmail.testingzone.example.com.');
});

/**
 * Test the deletion of a record set
 */
it('can delete a recordSet', function() {
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $client->dns()->RecordSet()->delete('testing-zone',
    'testingmail.testingzone.example.com.', 'CNAME');
    expect($response->status->successful)->toBeTrue();
});
