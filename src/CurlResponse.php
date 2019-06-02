<?php

namespace CurlClient;

use CurlClient\Interfaces\ICurlResponse;

/**
 * Class CurlResponse
 * @package CurlClient
 */
class CurlResponse implements ICurlResponse
{
    /** @var  $handle */
    protected $handle;

    /** @var  $content */
    protected $content;

    /**
     * Checks if it's a 2xx success response
     * @return bool
     */
    public function isOk()
    {
        $info = $this->getInfo();
        if(substr($info['http_code'], 0, 1) === '2'){
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
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getInfo(){
        return curl_getinfo($this->handle);
    }
}