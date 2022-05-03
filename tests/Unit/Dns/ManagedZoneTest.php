<?php

/**
 * Test getting a managed zone's information
 */
it('can get a specific managed zone', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $testing->dns()->managedZone()->get('testing-zone');
    expect($response->status->code)->toBe(200);
});

/**
 * Test listing all managed zones of a project
 */
it('can list managed zones', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $testing->dns()->managedZone()->list();
    expect($response->status->code)->toBe(200);
    expect($response->json)->toBeJson();
    expect($response->object)->toBeObject();
});

/**
 * Test creation of a new managed zone
 */
it('can create a new zone', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $testing->dns()->managedZone()->create(
        [
            'name' => 'testing-zone-3',
            'dns_name' => 'testing-zone-3.example.com.',
            'visibility' => 'private',
            'dnssec_config_state' => 'off',
            'description' => 'Testing zone 3 by SDK',
        ]);
    expect($response->status->code)->toBe(200);
});

/**
 * Test deletion of a managed zone
 */
it('can delete a zone', function() {
    $testing = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $testing->dns()->managedZone()->delete('testing-zone-3');
    expect($response->status->successful)->toBeTrue();
});

