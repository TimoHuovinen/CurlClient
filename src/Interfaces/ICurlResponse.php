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
     * @return mixed
     */
    public function setHandle($handle);

    /**
     * @return mixed
     */
    public function getHandle();

    /**
     * @param $content
     * @return mixed
     */
    public function setContent($content);
}