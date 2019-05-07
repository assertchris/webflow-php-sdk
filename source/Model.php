<?php

namespace Gitstore\Webflow;

abstract class Model
{

    public function __get(string $property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        throw new Exception("{$property} is not defined");
    }
}
