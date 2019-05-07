<?php

namespace Gitstore\Webflow;

interface Effect
{
    public function wasSuccessful(): bool;

    public function getModel(): Model;
}
