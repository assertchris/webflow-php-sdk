<?php

namespace Gitstore\Webflow\Iterators;

use Gitstore\Webflow\Iterator;
use Gitstore\Webflow\Models\Item;

class Items extends Iterator
{
    protected function getGenerator(): \Generator
    {
        foreach ($this->response->getData() as $item) {
            yield new Item(
                $this->response->withBody(json_encode($item))
            );
        }
    }
}
