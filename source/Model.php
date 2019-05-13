<?php

namespace Gitstore\Webflow;

use Gitstore\Webflow\Exceptions\PropertyNotDefinedException;

abstract class Model
{
    protected $response;
    protected $data = [];

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function __call(string $method, array $parameters = [])
    {
        return $this->response->{$method}(...$parameters);
    }

    public function __get(string $property)
    {
        if (isset($this->data[$property])) {
            return $this->data[$property];
        }

        $class = get_class($this);

        throw new PropertyNotDefinedException("{$property} is not defined for {$class}");
    }
}
