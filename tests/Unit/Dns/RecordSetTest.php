<?php

use Glamstack\GoogleCloud\Exceptions\RecordSetException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

it('can reach recordset', function () {
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $testing->dns()->recordSet()->list('testing-zone');
    expect($response->status->code)->toBe(200);
});

it('can reach recordset with custom configuration', function () {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'email' => 'dwheeler@gitlab.com',
        'file_path' => 'storage/keys/glamstack-google/test.json',
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
        'file_path' => 'storage/keys/glamstack-google/test.json',
        'log_channels' => ['single'],
        'project_id' => 'dwheeler-277df745'
    ]);
    $client->dns()->recordSet()->create('testing-zone', [
        'testing' => 'this should not work'
    ]);
})->throws(UndefinedOptionsException::class);

it('does not have the required type for rrdatas', function() {
    $client = new Glamstack\GoogleCloud\ApiClient(null, [
        'api_scopes' => ['https://www.googleapis.com/auth/ndev.clouddns.readwrite'],
        'email' => 'dwheeler@gitlab.com',
        'file_path' => 'storage/keys/glamstack-google/test.json',
        'log_channels' => ['single'],
        'project_id' => 'dwheeler-277df745'
    ]);
    $client->dns()->recordSet()->create('testing-zone', [
        'name' => 'TestingSet',
        'ttl' => 300,
        'type' => 'CNAME',
        'rrdatas' => 'mail.testingzone.example.com.'
    ]);
})->throws(InvalidOptionsException::class);

it('can create a recordSet', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
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
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $testing->dns()->RecordSet()->delete('testing-zone',
    'testingmail.testingzone.example.com.', 'CNAME');
    expect($response->status->successful)->toBeTrue();
});
