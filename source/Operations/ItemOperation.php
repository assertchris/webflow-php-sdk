<?php

namespace Gitstore\Webflow\Operations;

use Gitstore\Webflow\Model;
use Gitstore\Webflow\Models\ItemModel;
use Gitstore\Webflow\Operation;

abstract class ItemOperation extends Operation
{
    public function getModel(): Model
    {
        return new ItemModel($this->response);
    }
}
