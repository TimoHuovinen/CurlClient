<?php

namespace CurlClient\Interfaces;

/**
 * Interface ICurlResponse
 * @package CurlClient\Interfaces
 */
interface ICurlResponse
{
    /**
     * @param resource $handle
     */
    public function setHandle($handle);

    /**
     * @return resource
     */
    public function getHandle();

    /**
     * @param callable $headerCallback
     */
    public function setHeaderHandle(callable $headerCallback);

    /**
     * @param callable $bodyCallback
     */
    public function setBodyHandle(callable $bodyCallback);

    /**
     * A helper hook to be extended that is called when the request has been completed
     */
    public function onComplete();
}