<?php

namespace CurlClient;

use CurlClient\Interfaces\ICurlRequest;

/**
 * Class CurlRequest
 * @package CurlClient
 */
class CurlRequest implements ICurlRequest
{
    /** @var array $options */
    protected $options = [
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
    ];

    /**
     * CurlRequest constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = array_replace($this->options, $options);
    }
}