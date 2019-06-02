<?php

namespace CurlClient\Interfaces;

interface ICurlClient
{
    public function add(ICurlRequest $request, ICurlResponse $response);

    public function send();
}