<?php

namespace Gitstore\Webflow;

use Gitstore\Webflow\Iterators\{Collections, Items, Sites};
use Gitstore\Webflow\Models\{Collection, Item, Site};
use Gitstore\Webflow\Operations\{ItemCreated, ItemDeleted, ItemUpdated};

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
