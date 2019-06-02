<?php

require_once 'src/Promise.php';
require_once 'src/Interfaces/ICurlRequest.php';
require_once 'src/Interfaces/ICurlResponse.php';
require_once 'src/CurlResponse.php';
require_once 'src/CurlRequest.php';
require_once 'src/CurlClient.php';

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
   // var_dump($response->getContent());
   // var_dump(curl_getinfo($response->getHandle()));
});

class GoogleHomeHeadersRequest extends CurlRequest
{
    public function getOptions()
    {
        return array_replace($this->options, [
            CURLOPT_URL => 'https://google.com',
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS => 5,
            // we only fetch the headers
            CURLOPT_NOBODY => 1,
        ]);
    }
}

/**
 * Handle the response, fetching all the data that we actually want
 *
 * Class GoogleDateResponse
 */
class GoogleDateResponse extends CurlResponse
{
    /** @var \DateTime $date */
    public $date;
    public function onComplete()
    {
        $this->date = new \DateTime($this->getLastHeader('date'));
    }
    public function setBodyHandle(callable $bodyCallback)
    {
        // we ignore it on purpose to save memory
    }
}

// add request 2
$client->add(new GoogleHomeHeadersRequest, new GoogleDateResponse())->then(function (GoogleDateResponse $response) {
    if($response->isOk()){
        var_dump($response->date->format('Y-m-d H:i:s T'));
    }
});

// send the requests
$client->send();