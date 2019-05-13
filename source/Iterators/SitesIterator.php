<?php

namespace Gitstore\Webflow\Iterators;

use Generator;
use Gitstore\Webflow\Iterator;
use Gitstore\Webflow\Models\SiteModel;

class SitesIterator extends Iterator
{
    protected function getGenerator(): Generator
    {
        foreach ($this->response->getData() as $item) {
            yield new SiteModel(
                $this->response->withBody(json_encode($item))
            );
        }
    }
}
