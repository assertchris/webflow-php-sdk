<?php

namespace Gitstore\Webflow;

abstract class Iterator implements \IteratorAggregate, \ArrayAccess
{
    protected $response;
    protected $cached;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getIterator(): \Iterator
    {
        $this->warm();

        return new \ArrayIterator($this->cached);
    }

    private function warm()
    {
        if (is_null($this->cached)) {
            $this->cached = iterator_to_array($this->getGenerator());
        }
    }

    public function offsetExists($index)
    {
        $this->warm();

        return isset($this->cached[$index]);
    }

    public function offsetGet($index)
    {
        $this->warm();

        return $this->cached[$index];
    }

    public function offsetSet($index, $value)
    {
        $this->warm();
        $this->cached[$index] = $value;
    }

    public function offsetUnset($index)
    {
        $this->warm();
        unset($this->cached[$index]);
    }

    public function __call(string $method, array $parameters = [])
    {
        return $this->response->{$method}(...$parameters);
    }

    public function __get(string $property)
    {
        return $this->response->{$property};
    }

    abstract protected function getGenerator(): \Generator;
}
