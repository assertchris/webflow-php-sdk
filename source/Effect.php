<?php

namespace Gitstore\Webflow;

use Gitstore\Webflow\Model;

interface Effect
{
    public function wasSuccessful(): bool;
    public function getModel(): Model;
}
