<?php

namespace Gitstore\Webflow\Iterators;

use Generator;
use Gitstore\Webflow\Iterator;
use Gitstore\Webflow\Models\CollectionModel;

class CollectionsIterator extends Iterator
{
    protected function getGenerator(): Generator
    {
        foreach ($this->response->getData() as $item) {
            yield new CollectionModel(
                $this->response->withBody(json_encode($item))
            );
        }
    }
}
