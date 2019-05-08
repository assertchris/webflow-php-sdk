<?php

namespace Gitstore\Webflow\Iterators;

use Gitstore\Webflow\Iterator;
use Gitstore\Webflow\Models\Collection;

class Collections extends Iterator
{
    protected function getGenerator(): \Generator
    {
        foreach ($this->response->getData() as $item) {
            yield new Collection(
                $this->response->withBody(json_encode($item))
            );
        }
    }
}
