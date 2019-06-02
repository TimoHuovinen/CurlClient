<?php

namespace CurlClient;

use CurlClient\Interfaces\ICurlRequest;

class CurlRequest implements ICurlRequest
{
    private $options = [
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
    ];

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = array_replace($this->options, $options);
    }
}