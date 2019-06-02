<?php

require_once 'src/Promise.php';
require_once 'src/Interfaces/ICurlClient.php';
require_once 'src/Interfaces/ICurlRequest.php';
require_once 'src/Interfaces/ICurlResponse.php';
require_once 'src/CurlResponse.php';
require_once 'src/CurlRequest.php';
require_once 'src/CurlClient.php';

use \CurlClient\CurlClient;
use \CurlClient\CurlRequest;
use \CurlClient\CurlResponse;
use \CurlClient\Interfaces\ICurlRequest;

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


class GoogleRequest implements ICurlRequest
{
    public function getOptions()
    {
        return [
            CURLOPT_URL => 'https://google.com',
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS => 5,
        ];
    }
}


// add request 2
$client->add(new GoogleRequest, new CurlResponse())->then(function (CurlResponse $response) {
    var_dump($response->getContent());
});

// send the requests
$client->send();