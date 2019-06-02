<?php

namespace CurlClient;

use CurlClient\Interfaces\ICurlResponse;

class CurlResponse implements ICurlResponse
{
    protected $handle;
    protected $body;

    public function isOk()
    {
        $info = $this->getInfo();
        if(substr($info['http_code'], 0, 1) === '2'){
            return true;
        }
        return false;
    }

    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function setContent($body)
    {
        $this->body = $body;
    }

    public function getContent()
    {
        return $this->body;
    }

    public function getInfo(){
        return curl_getinfo($this->handle);
    }
}