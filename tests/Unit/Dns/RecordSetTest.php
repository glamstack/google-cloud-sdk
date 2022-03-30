<?php

use Glamstack\GoogleCloud\Exceptions\RecordSetException;

it('can reach recordset', function () {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->recordSet()->list('testing-zone');
    expect($response->status->code)->toBe(200);
});

it('can reach recordset with custom configuration', function () {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'email' => 'dwheeler@gitlab.com',
        'file_path' => 'storage/keys/glamstack-google/gcp_project_1.json',
        'log_channels' => ['single'],
        'project_id' => 'dwheeler-277df745'
    ]);
    $response = $client->dns()->recordSet()->list('testing-zone');
    expect($response->status->code)->toBe(200);
});

it('does not meet the record requirements', function () {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'email' => 'dwheeler@gitlab.com',
        'file_path' => 'storage/keys/glamstack-google/gcp_project_1.json',
        'log_channels' => ['single'],
        'project_id' => 'dwheeler-277df745'
    ]);
    $recordSet = $client->dns()->recordSet()->create('testing-zone', [
        'testing' => 'this should not work'
    ]);
})->throws(RecordSetException::class, 'The Record Set is missing a required parameter');

it('can create a recordSet', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->recordSet()->create('testing-zone', [
        'name' => 'testingmail.testingzone.example.com.',
        'type' => 'CNAME',
        'ttl' => 300,
        'rrdatas' => ['mail.testingzone.example.com.']
        ]
    );
    expect($response->status->code)->toBe(200);
});

it('can delete a recordSet', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->RecordSet()->delete('testing-zone',
    'testingmail.testingzone.example.com.', 'CNAME');
    expect($response->status->successful)->toBeTrue();
});
