<?php

namespace Gitstore\Webflow\Clients;

use Gitstore\Webflow\Client as GitstoreClient;
use Gitstore\Webflow\Exception as GitstoreException;
use Gitstore\Webflow\Iterators\{Collections, Items, Sites};
use Gitstore\Webflow\Models\{Collection, Item, Site};
use Gitstore\Webflow\Operations\{ItemCreated, ItemDeleted, ItemUpdated};
use Gitstore\Webflow\Response;
use GuzzleHttp\Client as BaseGuzzleClient;

class GuzzleClient implements GitstoreClient
{
    private $token;
    private $guzzle;
    private $base = "https://api.webflow.com";
    private $version = "1.0.0";

    public function __construct(string $token, BaseGuzzleClient $guzzle = null)
    {
        $this->token = $token;

        if (is_null($guzzle)) {
            $this->guzzle = new BaseGuzzleClient([
                "base_uri" => $this->base,
            ]);
        }
    }

    private function request(string $method, string $path, array $data = []): Response
    {
        try {
            $response = $this->guzzle->request($method, $path, [
                "headers" => [
                    "Authorization" => "Bearer {$this->token}",
                    "Accept-Version" => $this->version,
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                ],
            ]);
        } catch (\Exception $e) {
            throw new GitstoreException(
                $e->getMessage(),
                $e->getLine()
            );
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

    }
    
    public function getCollection(string $collectionId): Collection
    {

    }
    
    public function getItems(string $collectionId): Items
    {

    }
    
    public function getItem(string $collectionId, string $itemId): Item
    {

    }
    
    public function createItem(string $collectionId, array $data): ItemCreated
    {

    }
    
    public function updateItem(string $collectionId, string $itemId, array $data): ItemUpdated
    {

    }
    
    public function deleteItem(string $collectionId, string $itemId): ItemDeleted
    {

    }
}
