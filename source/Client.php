<?php

namespace Gitstore\Webflow;

use Gitstore\Webflow\Exceptions\RequestFailedException;
use Gitstore\Webflow\Iterators\CollectionsIterator;
use Gitstore\Webflow\Iterators\ItemsIterator;
use Gitstore\Webflow\Iterators\SitesIterator;
use Gitstore\Webflow\Models\CollectionModel;
use Gitstore\Webflow\Models\ItemModel;
use Gitstore\Webflow\Models\SiteModel;
use Gitstore\Webflow\Operations\ItemCreatedOperation;
use Gitstore\Webflow\Operations\ItemDeletedOperation;
use Gitstore\Webflow\Operations\ItemUpdatedOperation;

abstract class Client
{
    const BASE = "https://api.webflow.com";
    const VERSION = "1.0.0";

    public function getSites(): SitesIterator
    {
        return new SitesIterator($this->request("GET", "/sites"));
    }

    public function getSite(string $siteId): SiteModel
    {
        return new SiteModel($this->request("GET", "/sites/{$siteId}"));
    }

    public function getCollections(string $siteId): CollectionsIterator
    {
        return new CollectionsIterator($this->request("GET", "/sites/{$siteId}/collections"));
    }

    public function getCollection(string $collectionId): CollectionModel
    {
        return new CollectionModel($this->request("GET", "/collections/{$collectionId}"));
    }

    public function getItems(string $collectionId): ItemsIterator
    {
        return new ItemsIterator($this->request("GET", "/collections/{$collectionId}/items"));
    }

    public function getItem(string $collectionId, string $itemId): ItemModel
    {
        $response = $this->request("GET", "/collections/{$collectionId}/items/{$itemId}");

        // why on earth is webflow returning a paginated response for this?!
        $response = $response->withBody(json_encode($response->getData()["items"][0]));

        return new ItemModel($response);
    }

    public function createItem(string $collectionId, array $data): ItemCreatedOperation
    {
        try {
            return new ItemCreatedOperation($this->request("POST", "/collections/{$collectionId}/items", [
                "fields" => array_merge([
                    "_archived" => false,
                    "_draft" => false,
                ], $data),
            ]), true);
        } catch (RequestFailedException $exception) {
            $response = $exception->getPrevious()->getResponse();

            return new ItemCreatedOperation(new Response(
                $response->getHeaders(),
                (string) $response->getBody()
            ), false);
        }
    }

    public function updateItem(string $collectionId, string $itemId, array $data): ItemUpdatedOperation
    {
        try {
            return new ItemUpdatedOperation($this->request("PUT", "/collections/{$collectionId}/items/{$itemId}", [
                "fields" => $data,
            ]), true);
        } catch (RequestFailedException $exception) {
            $response = $exception->getPrevious()->getResponse();

            return new ItemUpdatedOperation(new Response(
                $response->getHeaders(),
                (string) $response->getBody()
            ), false);
        }
    }

    public function deleteItem(string $collectionId, string $itemId): ItemDeletedOperation
    {
        try {
            return new ItemDeletedOperation($this->request("DELETE", "/collections/{$collectionId}/items/{$itemId}"), true);
        } catch (RequestFailedException $exception) {
            $response = $exception->getPrevious()->getResponse();

            return new ItemDeletedOperation(new Response(
                $response->getHeaders(),
                (string) $response->getBody()
            ), false);
        }
    }

    abstract protected function request(string $method, string $path, array $data = []): Response;
}
