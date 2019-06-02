<?php

namespace CurlClient;

class Promise
{
    protected $handle;

    public function __construct(callable $handle)
    {
        $this->handle = $handle;
    }

    public function then(callable $resolve = null, callable $reject = null)
    {
        $handle = $this->handle;
        $handle($resolve, $reject);
    }
}
