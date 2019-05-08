<?php

namespace Gitstore\Webflow\Models;

use Carbon\Carbon;
use Gitstore\Webflow\Model;
use Gitstore\Webflow\Response;

class Collection extends Model
{
    protected $response;
    protected $id;
    protected $name;
    protected $slug;
    protected $singularName;
    protected $createdAt;
    protected $updatedAt;
    protected $extra;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $data = $response->getData();

        $this->id = $data["_id"];
        $this->name = $data["name"];
        $this->slug = $data["slug"];
        $this->singularName = $data["singularName"];
        $this->createdAt = new Carbon($data["createdOn"]);
        $this->updatedAt = new Carbon($data["lastUpdated"]);

        unset(
            $data["_id"],
            $data["name"],
            $data["slug"],
            $data["singularName"],
            $data["createdOn"],
            $data["lastUpdated"]
        );

        $this->extra = $data;
    }
}
