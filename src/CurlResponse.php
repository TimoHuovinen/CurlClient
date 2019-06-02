<?php

namespace CurlClient;

use CurlClient\Interfaces\ICurlResponse;

/**
 * Class CurlResponse
 * @package CurlClient
 */
class CurlResponse implements ICurlResponse
{
    /** @var $handle */
    protected $handle;

    /** @var string $body */
    protected $body;

    /** @var array $headers */
    protected $headers;

    /**
     * Checks if it's a 2xx success response
     * @return bool
     */
    public function isOk()
    {
        $info = $this->getInfo();
        if (substr($info['http_code'], 0, 1) === '2') {
            return true;
        }
        return false;
    }

    /**
     * @param $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param callable $bodyCallback
     */
    public function setBodyHandle(callable $bodyCallback)
    {
        $this->body = $bodyCallback();
    }

    /**
     * @param callable $headerCallback
     */
    public function setHeaderHandle(callable $headerCallback)
    {
        $this->addHeader($headerCallback());
    }

    /**
     * A helper hook to be extended that is called when the request has been completed
     */
    public function onComplete()
    {
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return curl_getinfo($this->handle);
    }

    /**
     * @param string $header
     */
    public function addHeader(string $header)
    {
        $header = explode(':', $header, 2);
        if (count($header) < 2) { // ignore invalid headers
            return;
        }
        $this->headers[strtolower(trim($header[0]))][] = trim($header[1]);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getLastHeader($key)
    {
        $headers = $this->headers[$key] ?? [];
        return array_pop($headers);
    }
}
