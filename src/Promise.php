<?php

namespace CurlClient;

/**
 * TODO: add finally and catch
 *
 * Class Promise
 * @package CurlClient
 */
class Promise
{
    /** @var callable $executor */
    protected $executor;

    /**
     * Promise constructor.
     * @param callable $executor
     */
    public function __construct(callable $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param callable|null $resolve
     * @param callable|null $reject
     */
    public function then(callable $resolve = null, callable $reject = null)
    {
        $executor = $this->executor;
        $executor($resolve, $reject);
    }
}
