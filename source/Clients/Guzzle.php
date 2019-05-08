<?php

namespace Gitstore\Webflow\Clients;

use Gitstore\Webflow\Client;
use Gitstore\Webflow\Exception as GitstoreException;
use Gitstore\Webflow\Iterators\Collections;
use Gitstore\Webflow\Iterators\Items;
use Gitstore\Webflow\Iterators\Sites;
use Gitstore\Webflow\Models\Collection;
use Gitstore\Webflow\Models\Item;
use Gitstore\Webflow\Models\Site;
use Gitstore\Webflow\Operations\ItemCreated;
use Gitstore\Webflow\Operations\ItemDeleted;
use Gitstore\Webflow\Operations\ItemUpdated;
use Gitstore\Webflow\Response;
use GuzzleHttp\Client as BaseClient;

class Guzzle implements Client
{
    private $token;
    private $guzzle;
    private $base = "https://api.webflow.com";
    private $version = "1.0.0";

    public function __construct(string $token, BaseClient $guzzle = null)
    {
        $this->token = $token;

        if (is_null($guzzle)) {
            $this->guzzle = new BaseClient([
                "base_uri" => $this->base,
            ]);
        }
    }

    private function request(string $method, string $path, array $data = []): Response
    {
        $parameters = [
            "headers" => [
                "Authorization" => "Bearer {$this->token}",
                "Accept-Version" => $this->version,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
        ];

        if ($method === "POST" || $method === "PUT" || $method === "PATCH") {
            $parameters["body"] = json_encode($data);
        }

        try {
            $response = $this->guzzle->request($method, $path, $parameters);
        } catch (\Exception $e) {
            throw new GitstoreException($e->getMessage(), $e->getCode(), $e);
        }

        return new Response(
            $response->getHeaders(),
            (string) $response->getBody()
        );
    }

    public function getSites(): Sites
    {
        return new Sites($this->request("GET", "/sites"));
    }

    public function getSite(string $siteId): Site
    {
        return new Site($this->request("GET", "/sites/{$siteId}"));
    }

    public function getCollections(string $siteId): Collections
    {
        return new Collections($this->request("GET", "/sites/{$siteId}/collections"));
    }

    public function getCollection(string $collectionId): Collection
    {
        return new Collection($this->request("GET", "/collections/{$collectionId}"));
    }

    public function getItems(string $collectionId): Items
    {
        return new Items($this->request("GET", "/collections/{$collectionId}/items"));
    }

    public function getItem(string $collectionId, string $itemId): Item
    {
    }

    public function createItem(string $collectionId, array $data): ItemCreated
    {
        try {
            return new ItemCreated($this->request("POST", "/collections/{$collectionId}/items", [
                "fields" => array_merge([
                    "_archived" => false,
                    "_draft" => false,
                ], $data),
            ]), true);
        }
        catch (\Exception $e) {
            $response = $e->getPrevious()->getResponse();

            return new ItemCreated(new Response(
                $response->getHeaders(),
                (string) $response->getBody()
            ), false);
        }
    }

    public function updateItem(string $collectionId, string $itemId, array $data): ItemUpdated
    {
    }

    public function deleteItem(string $collectionId, string $itemId): ItemDeleted
    {
    }
}
