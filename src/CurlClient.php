<?php

namespace CurlClient;

use CurlClient\Exceptions\CurlException;
use CurlClient\Interfaces\ICurlRequest;
use CurlClient\Interfaces\ICurlResponse;

/**
 * Class CurlClient
 * @package CurlClient
 */
class CurlClient
{
    /** @var resource $handle */
    protected $handle;

    /** @var int $timeout */
    protected $timeout;

    /** @var array $requests */
    protected $requests = [];

    /**
     * CurlClient constructor.
     */
    public function __construct()
    {
        // timeout in seconds for all the requests to respond
        $this->timeout = 360;

        //create the multiple cURL handle
        $this->handle = curl_multi_init();
    }

    /**
     * Clear the remaining handle
     */
    public function __destruct()
    {
        curl_multi_close($this->handle);
    }

    /**
     * Adds a request instance with the curl parameters
     * and an optional response instance to map the response data
     *
     * @param ICurlRequest $request
     * @param ICurlResponse|null $response
     * @return Promise
     */
    public function add(ICurlRequest $request, ICurlResponse $response = null)
    {
        $client = $this;
        return new Promise(function (callable $resolve = null, callable $reject = null) use ($client, $request, $response) {

            // Fallback in case the response is null or false
            $response ?: new CurlResponse();

            // Create cURL resource
            $ch = curl_init();

            // Set the curl options
            $options = $request->getOptions();

            // Special CURL header handling
            if (!array_key_exists(CURLOPT_HEADERFUNCTION, $options)) {
                $options[CURLOPT_HEADERFUNCTION] = function ($handle, $header) use ($response) {
                    $response->setHeaderHandle(function () use ($header) {
                        return $header;
                    });
                    return strlen($header);
                };
            }

            // Set the curl options
            curl_setopt_array($ch, $options);

            // Add the curl handle to the curl multi handle
            curl_multi_add_handle($this->handle, $ch);

            // Add the request and the expected response into the collection
            $x = new \stdClass;
            $response->setHandle($ch);
            $x->request = $request;
            $x->response = $response;
            $x->resolve = $resolve;
            $x->reject = $reject;
            $client->requests[spl_object_hash($x)] = $x;

        });
    }

    /**
     * @param $handle
     * @param $header
     * @return int
     */
    protected function headerFunction($handle, $header)
    {
        $len = strlen($header);
        $header = explode(':', $header, 2);
        if (count($header) < 2) { // ignore invalid headers
            return $len;
        }
        $name = strtolower(trim($header[0]));
        $headers[$name][] = trim($header[1]);
        return $len;
    }

    /**
     * Sends all the requests in parallel
     */
    public function send()
    {
        // Execute the multi handle
        do {
            $status = curl_multi_exec($this->handle, $active);
            if ($active) {
                curl_multi_select($this->handle, $this->timeout);
            }
        } while ($active && $status == CURLM_OK);

        // Loop over the added curl handles
        foreach ($this->requests as $x) {
            $resolve = $x->resolve;
            $reject = $x->reject;
//            $finally = $x->finally;

            /** @var ICurlResponse $response */
            $response = $x->response;

            $ch = $response->getHandle();

            $errorCode = curl_errno($ch);

            if ($errorCode) {

                // the curl request failed
                $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                $error = new CurlException($url . ': ' . curl_error($ch), $errorCode);
                if (isset($reject)) {
                    $reject($error);
                }

            } else {

                // Success
                $response->setBodyHandle(function () use ($ch) {
                    return curl_multi_getcontent($ch);
                });
                $response->onComplete();
                if (isset($response)) {
                    $resolve($response);
                }
            }

//            $finally($response);

            //close the handle
            curl_multi_remove_handle($this->handle, $ch);
        }
    }
}
