<?php

use Glamstack\GoogleAuth\AuthClient;

it('will throw exception if missing connection_config parameter', function(){
   $client = new AuthClient([
       'subject_email' => 'example@example.com'
   ]);
})->expectExceptionMessage('The required option "api_scopes" is missing.');

