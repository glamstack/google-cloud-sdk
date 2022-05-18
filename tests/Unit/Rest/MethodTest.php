<?php

it('can use get request to get managed zones', function () {
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $client->rest()->get('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones', []);
    expect($response->status->code)->toBe(200);
});

it('can use post request to create a new managed zone', function () {
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $request_data = [
        'name' => 'testing-zone-3',
        'dns_name' => 'testing-zone-3.example.com.',
        'visibility' => 'private',
        'dnssec_config_state' => 'off',
        'description' => 'Testing zone 3 by SDK',
    ];
    $response = $client->rest()->post('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones', $request_data);
    expect($response->status->code)->toBe(200);
});

it('can use delete request to delete a managed zone', function () {
    $client = new Glamstack\GoogleCloud\ApiClient('test');
    $response = $client->rest()->delete('https://dns.googleapis.com/dns/v1/projects/' . env('GOOGLE_CLOUD_TEST_PROJECT_ID') . '/managedZones/testing-zone-3');
    expect($response->status->successful)->toBeTrue();
});
