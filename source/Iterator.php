<?php

namespace Gitstore\Webflow;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Generator;
use Gitstore\Webflow\Exceptions\MethodNotSupportedException;
use Iterator as BaseIterator;
use IteratorAggregate;

abstract class Iterator implements ArrayAccess, Countable, IteratorAggregate
{
    protected $response;
    protected $cached;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getIterator(): BaseIterator
    {
        $this->warm();

        return new ArrayIterator($this->cached);
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
        throw new MethodNotSupportedException("You can't add models to an iterator");
    }

    public function offsetUnset($index)
    {
        $this->warm();
        unset($this->cached[$index]);
    }

    public function count()
    {
        $this->warm();

        return count($this->cached);
    }

    public function __call(string $method, array $parameters = [])
    {
        return $this->response->{$method}(...$parameters);
    }

    abstract protected function getGenerator(): Generator;
}
