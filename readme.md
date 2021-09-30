# Webflow PHP SDK

A decent PHP Webflow client

## Getting started

```sh
composer require gitstore/webflow
```

## Basic usage

```php
use Gitstore\Webflow\Clients\GuzzleClient;

$client = new GuzzleClient(getenv("WEBFLOW_TOKEN"));

$sites = $client->getSites();
$collections = $client->getCollections($sites[0]->id);

$operation = $client->createItem($collections[0]->id, [
    "name" => "A new item",
]);

print $operation->wasSuccessful(); // true
print $operation->getModel()->id; // "5c7295..."
```

Check out the tests for more advanced usage.

## Feedback

Reach out [on Twitter](https://twitter.com/assertchris).
