<?php

require_once 'Promise.php';
require_once 'Interfaces/ICurlClient.php';
require_once 'Interfaces/ICurlRequest.php';
require_once 'Interfaces/ICurlResponse.php';
require_once 'CurlResponse.php';
require_once 'CurlRequest.php';
require_once 'CurlClient.php';

use \CurlClient\CurlClient;
use \CurlClient\CurlRequest;
use \CurlClient\CurlResponse;

header('Content-type: text/plain');

$client = new CurlClient();

// add request 1
$request = new CurlRequest([
    CURLOPT_URL => 'https://example.com'
]);
$response = new CurlResponse();
$client->add($request, $response)->then(function (CurlResponse $response) {
    var_dump($response->getContent());

    var_dump(curl_getinfo($response->getHandle()));
});

// add request 2
$request = new CurlRequest([
    CURLOPT_URL => 'https://google.com'
]);
$response = new CurlResponse();
$client->add($request, $response)->then(function (CurlResponse $response) {
    var_dump($response->getContent());
});

// send the requests
$client->send();