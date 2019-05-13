<?php

namespace Gitstore\Webflow\Models;

use Carbon\Carbon;
use Gitstore\Webflow\Model;
use Gitstore\Webflow\Response;

class ItemModel extends Model
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $data = $response->getData();

        $this->data["id"] = $data["_id"];
        $this->data["slug"] = $data["slug"];
        $this->data["createdAt"] = new Carbon($data["created-on"]);
        $this->data["updatedAt"] = new Carbon($data["updated-on"]);
        $this->data["publishedAt"] = new Carbon($data["published-on"]);
        $this->data["isDraft"] = $data["_draft"];
        $this->data["isArchived"] = $data["_archived"];

        unset(
            $data["_id"],
            $data["slug"],
            $data["created-on"],
            $data["updated-on"],
            $data["published-on"],
            $data["_draft"],
            $data["_archived"]
        );

        $this->data = array_merge($this->data, $data);
    }
}
