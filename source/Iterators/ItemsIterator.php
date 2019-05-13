<?php

namespace Gitstore\Webflow\Iterators;

use Generator;
use Gitstore\Webflow\PaginatedIterator;
use Gitstore\Webflow\Models\ItemModel;
use Gitstore\Webflow\Response;

class ItemsIterator extends PaginatedIterator
{
    protected $count;
    protected $limit;
    protected $offset;
    protected $total;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $data = $response->getData();

        $this->count = $data["count"];
        $this->limit = $data["limit"];
        $this->offset = $data["offset"];
        $this->total = $data["total"];
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    protected function getGenerator(): Generator
    {
        foreach ($this->response->getData()["items"] as $item) {
            yield new ItemModel(
                $this->response->withBody(json_encode($item))
            );
        }
    }
}
