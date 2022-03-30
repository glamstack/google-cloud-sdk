<?php

it('can get a specific managed zone', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->managedZone()->get('testing-zone');
    expect($response->status->code)->toBe(200);
});

it('can list managed zones', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->managedZone()->list();
    expect($response->status->code)->toBe(200);
    expect($response->json)->toBeJson();
    expect($response->object)->toBeObject();
});

it('can create a new zone', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->managedZone()->create(
        'testing-zone-3', 'testing-zone-3.example.com.',
        'private', 'off', 'Testing zone 3 by SDK');
    expect($response->status->code)->toBe(200);
});

it('can delete a zone', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('gcp_project_1');
    $response = $testing->dns()->managedZone()->delete('testing-zone-3');
    expect($response->status->successful)->toBeTrue();
});

