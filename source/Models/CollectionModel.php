<?php

namespace Gitstore\Webflow\Models;

use Carbon\Carbon;
use Gitstore\Webflow\Model;
use Gitstore\Webflow\Response;

class CollectionModel extends Model
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $data = $response->getData();

        $this->data["id"] = $data["_id"];
        $this->data["name"] = $data["name"];
        $this->data["slug"] = $data["slug"];
        $this->data["singularName"] = $data["singularName"];
        $this->data["createdAt"] = new Carbon($data["createdOn"]);
        $this->data["updatedAt"] = new Carbon($data["lastUpdated"]);

        unset(
            $data["_id"],
            $data["name"],
            $data["slug"],
            $data["singularName"],
            $data["createdOn"],
            $data["lastUpdated"]
        );

        $this->data = array_merge($this->data, $data);
    }
}
