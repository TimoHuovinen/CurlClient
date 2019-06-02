# CurlClient
Wrapper Class for CURL


Allow to customise the request and response class while still using curl to do the requests asynchronously

```
class GetUSDCurrenyRequest implements ICurlRequest { ... }
class USDCurrencyResponse implements ICurlResponse { ... }

$request = new GetUSDCurrenyRequest;
$response = new USDCurrencyResponse;

$client = new CurlClient;
$client->add($request, $response)->then(function(USDCurrencyResponse $response){
    echo $response->usdValue;
});
$client->send();
```
