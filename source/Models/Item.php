<?php

namespace Gitstore\Webflow\Models;

use Carbon\Carbon;
use Gitstore\Webflow\Model;
use Gitstore\Webflow\Response;

class Item extends Model
{
    protected $response;
    protected $id;
    protected $slug;
    protected $createdAt;
    protected $updatedAt;
    protected $publishedAt;
    protected $isDraft;
    protected $isArchived;
    protected $extra;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $data = $response->getData();

        $this->id = $data["_id"];
        $this->slug = $data["slug"];
        $this->createdAt = new Carbon($data["created-on"]);
        $this->updatedAt = new Carbon($data["updated-on"]);
        $this->publishedAt = new Carbon($data["published-on"]);
        $this->isDraft = $data["_draft"];
        $this->isArchived = $data["_archived"];

        unset(
            $data["_id"],
            $data["slug"],
            $data["created-on"],
            $data["updated-on"],
            $data["published-on"],
            $data["_draft"],
            $data["_archived"]
        );

        $this->extra = $data;
    }
}
