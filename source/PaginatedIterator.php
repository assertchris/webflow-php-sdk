<?php

namespace Gitstore\Webflow;

abstract class PaginatedIterator extends Iterator
{
    abstract public function getCount(): int;

    abstract public function getLimit(): int;

    abstract public function getOffset(): int;

    abstract public function getTotal(): int;
}
