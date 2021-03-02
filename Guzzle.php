<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$response = $client->request('GET', 'http://localhost/_Apotik-rest-server', [
    'query' => [
        'APOTIK-API-KEY' => 'Apotik123',
        'id' => '60400001'
    ]
]);

$result = json_decode($response->getBody()->getContents(), true);
var_dump($result);
?>