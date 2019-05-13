<?php

namespace Gitstore\Webflow\Models;

use Carbon\Carbon;
use Gitstore\Webflow\Model;
use Gitstore\Webflow\Response;

class SiteModel extends Model
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $data = $response->getData();

        $this->data["id"] = $data["_id"];
        $this->data["name"] = $data["name"];
        $this->data["shortName"] = $data["shortName"];
        $this->data["previewUrl"] = $data["previewUrl"];
        $this->data["createdAt"] = new Carbon($data["createdOn"], $data["timezone"]);
        $this->data["publishedAt"] = new Carbon($data["lastPublished"], $data["timezone"]);

        unset(
            $data["_id"],
            $data["name"],
            $data["shortName"],
            $data["previewUrl"],
            $data["createdOn"],
            $data["lastPublished"]
        );

        $this->data = array_merge($this->data, $data);
    }
}
