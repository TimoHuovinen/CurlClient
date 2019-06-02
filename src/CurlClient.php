<?php

namespace CurlClient;

use CurlClient\Interfaces\ICurlClient;
use CurlClient\Interfaces\ICurlRequest;
use CurlClient\Interfaces\ICurlResponse;

class CurlClient implements ICurlClient
{
    protected $handle;
    protected $timeout;
    protected $requests = [];

    public function __construct()
    {
        // timeout in seconds for all the requests to respond
        $this->timeout = 360;

        //create the multiple cURL handle
        $this->handle = curl_multi_init();
    }

    public function __destruct()
    {
        curl_multi_close($this->handle);
    }

    public function add(ICurlRequest $request, ICurlResponse $response)
    {
        $self = $this;
        return new Promise(function (callable $resolve = null, callable $reject = null) use ($self, $request, $response) {

            // create cURL resource
            $ch = curl_init();

            // set the curl options
            curl_setopt_array($ch, $request->getOptions());

            // add the curl handle to the curl multi handle
            curl_multi_add_handle($this->handle, $ch);

            $x = new \stdClass;
            $response->setHandle($ch);
            $x->request = $request;
            $x->response = $response;
            $x->resolve = $resolve;
            $x->reject = $reject;

            $id = spl_object_hash($x);
            $self->requests[$id] = $x;

        });
    }

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

            if (curl_errno($ch)) {

                // the curl request failed
                $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                $error = new \Exception($url . ': ' . curl_error($ch), curl_errno($ch));
                if (isset($reject)) {
                    $reject($error);
                }

            } else {

                // Success
                $response->setContent(curl_multi_getcontent($ch));
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
