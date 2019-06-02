<?php

namespace CurlClient\Interfaces;

interface ICurlResponse
{
    public function isOk();

    public function setHandle($handle);

    public function getHandle();

    public function setContent($content);

    public function getContent();
}