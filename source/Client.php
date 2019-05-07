<?php

namespace Gitstore\Webflow;

use Gitstore\Webflow\Iterators\Collections;
use Gitstore\Webflow\Iterators\Items;
use Gitstore\Webflow\Iterators\Sites;
use Gitstore\Webflow\Models\Collection;
use Gitstore\Webflow\Models\Item;
use Gitstore\Webflow\Models\Site;
use Gitstore\Webflow\Operations\ItemCreated;
use Gitstore\Webflow\Operations\ItemDeleted;
use Gitstore\Webflow\Operations\ItemUpdated;

interface Client
{
    public function getSites(): Sites;

    public function getSite(string $siteId): Site;

    public function getCollections(string $siteId): Collections;

    public function getCollection(string $collectionId): Collection;

    public function getItems(string $collectionId): Items;

    public function getItem(string $collectionId, string $itemId): Item;

    public function createItem(string $collectionId, array $data): ItemCreated;

    public function updateItem(string $collectionId, string $itemId, array $data): ItemUpdated;

    public function deleteItem(string $collectionId, string $itemId): ItemDeleted;
}
