<?php

use Carbon\Carbon;
use Gitstore\Webflow\Clients\GuzzleClient;
use Gitstore\Webflow\Iterators\Collections;
use Gitstore\Webflow\Iterators\Items;
use Gitstore\Webflow\Iterators\Sites;
use Gitstore\Webflow\Models\Collection;
use Gitstore\Webflow\Models\Item;
use Gitstore\Webflow\Models\Site;
use Gitstore\Webflow\Operations\ItemCreated;
use Gitstore\Webflow\Operations\ItemDeleted;
use Gitstore\Webflow\Operations\ItemUpdated;

class GuzzleClientTest extends TestCase
{
    private function getClient()
    {
        return new GuzzleClient(getenv("WEBFLOW_TOKEN"));
    }

    /**
     * @covers Gitstore\Webflow\Clients\GuzzleClient
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
     * @covers Gitstore\Webflow\Clients\GuzzleClient
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
     * @covers Gitstore\Webflow\Clients\GuzzleClient
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
     * @covers Gitstore\Webflow\Clients\GuzzleClient
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
}
