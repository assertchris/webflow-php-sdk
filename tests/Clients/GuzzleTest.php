<?php

use Carbon\Carbon;
use Gitstore\Webflow\Clients\Guzzle;
use Gitstore\Webflow\Iterators\Collections;
use Gitstore\Webflow\Iterators\Items;
use Gitstore\Webflow\Iterators\Sites;
use Gitstore\Webflow\Models\Collection;
use Gitstore\Webflow\Models\Item;
use Gitstore\Webflow\Models\Site;
use Gitstore\Webflow\Operations\ItemCreated;
use Gitstore\Webflow\Operations\ItemDeleted;
use Gitstore\Webflow\Operations\ItemUpdated;

class GuzzleTest extends TestCase
{
    private function getClient()
    {
        return new Guzzle(getenv("WEBFLOW_TOKEN"));
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Iterator
     * @covers Gitstore\Webflow\Iterators\Sites
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetSites()
    {
        $client = $this->getClient();

        $sites = $client->getSites();

        $this->assertInstanceOf(Sites::class, $sites);
        $this->assertIsInt($sites->getRequestLimit());
        $this->assertIsInt($sites->getRequestsRemaining());
        $this->assertInstanceOf(Site::class, $sites[0]);
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\Site
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetSingleSite()
    {
        $client = $this->getClient();

        $site = $client->getSite(getenv("WEBFLOW_SITE_ID"));

        $this->assertInstanceOf(Site::class, $site);
        $this->assertIsInt($site->getRequestLimit());
        $this->assertIsInt($site->getRequestsRemaining());

        $this->assertIsString($site->id);
        $this->assertIsString($site->name);
        $this->assertIsString($site->shortName);
        $this->assertIsString($site->previewUrl);
        $this->assertInstanceOf(Carbon::class, $site->createdAt);
        $this->assertInstanceOf(Carbon::class, $site->publishedAt);
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Iterator
     * @covers Gitstore\Webflow\Iterators\Collections
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetCollections()
    {
        $client = $this->getClient();

        $collections = $client->getCollections(getenv("WEBFLOW_SITE_ID"));

        $this->assertInstanceOf(Collections::class, $collections);
        $this->assertIsInt($collections->getRequestLimit());
        $this->assertIsInt($collections->getRequestsRemaining());
        $this->assertInstanceOf(Collection::class, $collections[0]);
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\Collection
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetSingleCollection()
    {
        $client = $this->getClient();

        $collection = $client->getCollection(getenv("WEBFLOW_COLLECTION_ID"));

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertIsInt($collection->getRequestLimit());
        $this->assertIsInt($collection->getRequestsRemaining());

        $this->assertIsString($collection->id);
        $this->assertIsString($collection->name);
        $this->assertIsString($collection->slug);
        $this->assertIsString($collection->singularName);
        $this->assertInstanceOf(Carbon::class, $collection->createdAt);
        $this->assertInstanceOf(Carbon::class, $collection->updatedAt);
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Response
     */
    public function testCanHandleItemCreateErrors()
    {
        $client = $this->getClient();

        $data = json_decode(getenv("WEBFLOW_ITEM_CREATE_DATA_INVALID"), true);
        $operation = $client->createItem(getenv("WEBFLOW_COLLECTION_ID"), $data);

        $this->assertInstanceOf(ItemCreated::class, $operation);
        $this->assertFalse($operation->wasSuccessful());
        $this->assertIsString($operation->getData()["err"]);
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\Item
     * @covers Gitstore\Webflow\Response
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemCreated
     */
    public function testCanCreateItem()
    {
        $client = $this->getClient();

        $data = json_decode(getenv("WEBFLOW_ITEM_CREATE_DATA_VALID"), true);
        $operation = $client->createItem(getenv("WEBFLOW_COLLECTION_ID"), $data);

        $this->assertInstanceOf(ItemCreated::class, $operation);
        $this->assertTrue($operation->wasSuccessful());
        $this->assertInstanceOf(Item::class, $operation->getModel());
    }

    /**
     * @covers Gitstore\Webflow\Clients\Guzzle
     * @covers Gitstore\Webflow\Iterator
     * @covers Gitstore\Webflow\Iterators\Items
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetItems()
    {
        $client = $this->getClient();

        $items = $client->getItems(getenv("WEBFLOW_COLLECTION_ID"));

        $this->assertInstanceOf(Items::class, $items);
        $this->assertIsInt($items->getRequestLimit());
        $this->assertIsInt($items->getRequestsRemaining());
        $this->assertIsInt($items->getCount());
        $this->assertIsInt($items->getLimit());
        $this->assertIsInt($items->getOffset());
        $this->assertIsInt($items->getTotal());
        $this->assertInstanceOf(Item::class, $items[0]);
    }
}
