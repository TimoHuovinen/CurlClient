# CurlClient
Wrapper Class for CURL


Allow to customise the request and response class while still using curl to do the requests asynchronously

```
class GetUSDCurrenyRequest implements ICurlRequest { ... }
class USDCurrencyResponse implements ICurlResponse { 
   public $usdValue;
   ...
}

$request = new GetUSDCurrenyRequest;
$response = new USDCurrencyResponse;

$client = new CurlClient;
$client->add($request, $response)->then(function(USDCurrencyResponse $response){
    echo $response->usdValue;
});
$client->send();
```


Or to fetch the date from google

```
/**
 * Fetch only the headers
 * 
 * Class GoogleHomeHeadersRequest
 */
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

// add request
$client->add(new GoogleHomeHeadersRequest, new GoogleDateResponse())->then(function (GoogleDateResponse $response) {
    if($response->isOk()){
        var_dump($response->date->format('Y-m-d H:i:s T'));
    }
});

// send the requests
$client->send();
```
