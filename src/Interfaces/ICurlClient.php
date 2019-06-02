<?php

namespace CurlClient\Interfaces;

/**
 * Interface ICurlClient
 * @package CurlClient\Interfaces
 */
interface ICurlClient
{
    /**
     * Adds a request instance with the curl parameters
     * and an optional response instance to map the response data
     *
     * @param ICurlRequest $request
     * @param ICurlResponse $response
     * @return mixed
     */
    public function add(ICurlRequest $request, ICurlResponse $response);

    /**
     * Sends all the previously added requests in parallel
     *
     * @return mixed
     */
    public function send();
}