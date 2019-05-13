# gitstore/webflow

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

## Sponsor

We use this library, for Gitstore. We're serious about maintaining the quality and functionality, but we could do with your support. If you're a company, who can afford to support the ongoing maintenance and improvement of this library; please consider doing so.

<a href="https://enjoy.gitstore.app/repositories/assertchris/gitstore-webflow"><img src="https://enjoy.gitstore.app/repositories/assertchris/gitstore-webflow/plans/badge-82.svg"></a>

## Feedback

Reach out [on Twitter](https://twitter.com/assertchris).
