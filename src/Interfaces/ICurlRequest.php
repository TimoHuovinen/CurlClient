<?php

namespace CurlClient\Interfaces;

interface ICurlRequest
{
    public function getOptions();

    public function setOptions(array $options);
}