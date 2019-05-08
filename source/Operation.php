<?php

namespace Gitstore\Webflow;

abstract class Operation
{
    protected $response;

    public function __construct(Response $response, bool $wasSuccessful)
    {
        $this->response = $response;
        $this->wasSuccessful = $wasSuccessful;
    }

    public function __call(string $method, array $parameters = [])
    {
        return $this->response->{$method}(...$parameters);
    }

    public function wasSuccessful(): bool
    {
        return $this->wasSuccessful;
    }

    abstract public function getModel(): Model;
}
