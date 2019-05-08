<?php

namespace Gitstore\Webflow;

abstract class Model
{
    public function __call(string $method, array $parameters = [])
    {
        return $this->response->{$method}(...$parameters);
    }

    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
    }
}
