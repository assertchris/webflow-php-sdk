<?php

namespace Gitstore\Webflow;

use Gitstore\Webflow\Model;

interface Operation
{
    public function wasSuccessful(): bool;
    public function getModel(): Model;
}
