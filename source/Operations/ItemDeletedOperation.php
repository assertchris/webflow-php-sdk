<?php

namespace Gitstore\Webflow\Operations;

use Gitstore\Webflow\Exceptions\ModelNotReturnedException;
use Gitstore\Webflow\Model;

class ItemDeletedOperation extends ItemOperation
{
    public function getModel(): Model
    {
        $class = static::class;

        throw new ModelNotReturnedException("Model is not returned for {$class}");
    }
}
