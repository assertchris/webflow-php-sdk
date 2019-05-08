<?php

namespace Gitstore\Webflow\Operations;

use Gitstore\Webflow\Model;
use Gitstore\Webflow\Models\Item;
use Gitstore\Webflow\Operation;

class ItemCreated extends Operation
{

    public function getModel(): Model
    {
        return new Item($this->response);
    }
}
