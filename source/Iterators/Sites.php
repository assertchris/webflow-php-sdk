<?php

namespace Gitstore\Webflow\Iterators;

use Gitstore\Webflow\Iterator;
use Gitstore\Webflow\Models\Site;
use Gitstore\Webflow\Response;

class Sites extends Iterator
{
    protected function getGenerator(): \Generator
    {
        foreach ($this->response->getData() as $item) {
            yield new Site(
                $this->response->withBody(json_encode($item))
            );
        }
    }
}
