<?php

namespace Gitstore\Webflow\Models;

use Carbon\Carbon;
use Gitstore\Webflow\Model;
use Gitstore\Webflow\Response;

class Site extends Model
{
    protected $response;
    protected $id;
    protected $name;
    protected $shortName;
    protected $previewUrl;
    protected $createdAt;
    protected $publishedAt;
    protected $extra;

    public function __construct(Response $response)
    {
        $this->response = $response;

        $data = $response->getData();

        $this->id = $data["_id"];
        $this->name = $data["name"];
        $this->shortName = $data["shortName"];
        $this->previewUrl = $data["previewUrl"];
        $this->createdAt = new Carbon($data["createdOn"], $data["timezone"]);
        $this->publishedAt = new Carbon($data["lastPublished"], $data["timezone"]);

        unset(
            $data["_id"],
            $data["name"],
            $data["shortName"],
            $data["previewUrl"],
            $data["createdOn"],
            $data["lastPublished"]
        );

        $this->extra = $data;
    }
}
