<?php

use Carbon\Carbon;
use Gitstore\Webflow\Clients\GuzzleClient;
use Gitstore\Webflow\Exceptions\MethodNotSupportedException;
use Gitstore\Webflow\Exceptions\ModelNotReturnedException;
use Gitstore\Webflow\Exceptions\PropertyNotDefinedException;
use Gitstore\Webflow\Iterators\CollectionsIterator;
use Gitstore\Webflow\Iterators\ItemsIterator;
use Gitstore\Webflow\Iterators\SitesIterator;
use Gitstore\Webflow\Models\CollectionModel;
use Gitstore\Webflow\Models\ItemModel;
use Gitstore\Webflow\Models\SiteModel;
use Gitstore\Webflow\Operations\ItemCreatedOperation;
use Gitstore\Webflow\Operations\ItemDeletedOperation;
use Gitstore\Webflow\Operations\ItemUpdatedOperation;

class GuzzleClientTest extends TestCase
{
    private $client;
    private $itemModel;

    private function getClient()
    {
        if (!$this->client) {
            $this->client = new GuzzleClient(getenv("WEBFLOW_TOKEN"));
        }

        return $this->client;
    }

    private function getItemModel()
    {
        $client = $this->getClient();

        if (!$this->itemModel) {
            $this->itemModel = $client
                ->createItem(getenv("WEBFLOW_COLLECTION_ID"), json_decode(getenv("WEBFLOW_ITEM_CREATE_DATA_VALID"), true))
                ->getModel();
        }

        return $this->itemModel;
    }

    private function deleteItemModel(ItemModel $item)
    {
        $client = $this->getClient();
        $client->deleteItem(getenv("WEBFLOW_COLLECTION_ID"), $item->id);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Iterator
     * @covers Gitstore\Webflow\Iterators\SitesIterator
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetSites()
    {
        $client = $this->getClient();

        $sites = $client->getSites();

        $this->assertInstanceOf(SitesIterator::class, $sites);
        $this->assertIsInt($sites->getRequestLimit());
        $this->assertIsInt($sites->getRequestsRemaining());

        $this->assertInstanceOf(SiteModel::class, $sites[0]);

        // test a few iterator things...

        $this->assertTrue(isset($sites[0]));

        foreach ($sites as $site) {
            $this->assertSame($site, $sites[0]);
            break;
        }

        unset($sites[0]);

        foreach ($sites as $site) {
            $this->assertNotSame($site, $sites[0]);
            break;
        }

        $this->expectException(MethodNotSupportedException::class);
        $sites[count($sites)] = "foo";
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\SiteModel
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetSingleSite()
    {
        $client = $this->getClient();

        $site = $client->getSite(getenv("WEBFLOW_SITE_ID"));

        $this->assertInstanceOf(SiteModel::class, $site);
        $this->assertIsInt($site->getRequestLimit());
        $this->assertIsInt($site->getRequestsRemaining());

        $this->assertIsString($site->id);
        $this->assertIsString($site->name);
        $this->assertIsString($site->shortName);
        $this->assertIsString($site->previewUrl);
        $this->assertInstanceOf(Carbon::class, $site->createdAt);
        $this->assertInstanceOf(Carbon::class, $site->publishedAt);

        // test a few model things...

        $this->expectException(PropertyNotDefinedException::class);
        $site->missing;
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Iterator
     * @covers Gitstore\Webflow\Iterators\CollectionsIterator
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetCollections()
    {
        $client = $this->getClient();

        $collections = $client->getCollections(getenv("WEBFLOW_SITE_ID"));

        $this->assertInstanceOf(CollectionsIterator::class, $collections);
        $this->assertIsInt($collections->getRequestLimit());
        $this->assertIsInt($collections->getRequestsRemaining());

        $this->assertInstanceOf(CollectionModel::class, $collections[0]);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\CollectionModel
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetSingleCollection()
    {
        $client = $this->getClient();

        $collection = $client->getCollection(getenv("WEBFLOW_COLLECTION_ID"));

        $this->assertInstanceOf(CollectionModel::class, $collection);
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
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemOperation
     * @covers Gitstore\Webflow\Operations\ItemCreatedOperation
     * @covers Gitstore\Webflow\Response
     */
    public function testCanHandleItemCreateErrors()
    {
        $client = $this->getClient();

        $data = json_decode(getenv("WEBFLOW_ITEM_CREATE_DATA_INVALID"), true);
        $operation = $client->createItem(getenv("WEBFLOW_COLLECTION_ID"), $data);

        $this->assertInstanceOf(ItemCreatedOperation::class, $operation);
        $this->assertFalse($operation->wasSuccessful());
        $this->assertIsString($operation->getData()["err"]);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\ItemModel
     * @covers Gitstore\Webflow\Response
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemOperation
     * @covers Gitstore\Webflow\Operations\ItemCreatedOperation
     */
    public function testCanCreateItem()
    {
        $client = $this->getClient();

        $data = json_decode(getenv("WEBFLOW_ITEM_CREATE_DATA_VALID"), true);
        $operation = $client->createItem(getenv("WEBFLOW_COLLECTION_ID"), $data);

        $this->assertInstanceOf(ItemCreatedOperation::class, $operation);
        $this->assertTrue($operation->wasSuccessful());
        $this->assertInstanceOf(ItemModel::class, $operation->getModel());

        // ...clean up
        $this->deleteItemModel($operation->getModel());
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemOperation
     * @covers Gitstore\Webflow\Operations\ItemUpdatedOperation
     * @covers Gitstore\Webflow\Response
     */
    public function testCanHandleItemUpdateErrors()
    {
        $client = $this->getClient();
        $model = $this->getItemModel();

        $data = json_decode(getenv("WEBFLOW_ITEM_UPDATE_DATA_VALID"), true);
        $data["_archived"] = $model->isArchived;
        $data["_draft"] = $model->isDraft;
        $data["slug"] = $model->slug;

        $operation = $client->updateItem(getenv("WEBFLOW_COLLECTION_ID"), "missing", $data);

        $this->assertInstanceOf(ItemUpdatedOperation::class, $operation);
        $this->assertFalse($operation->wasSuccessful());

        $data = json_decode(getenv("WEBFLOW_ITEM_UPDATE_DATA_INVALID"), true);
        $operation = $client->updateItem(getenv("WEBFLOW_COLLECTION_ID"), $model->id, $data);

        $this->assertInstanceOf(ItemUpdatedOperation::class, $operation);
        $this->assertFalse($operation->wasSuccessful());
        $this->assertIsString($operation->getData()["err"]);

        // ...clean up
        $this->deleteItemModel($model);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\ItemModel
     * @covers Gitstore\Webflow\Response
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemOperation
     * @covers Gitstore\Webflow\Operations\ItemUpdatedOperation
     */
    public function testCanUpdateItem()
    {
        $client = $this->getClient();
        $model = $this->getItemModel();

        $data = json_decode(getenv("WEBFLOW_ITEM_UPDATE_DATA_VALID"), true);
        $data["_archived"] = $model->isArchived;
        $data["_draft"] = $model->isDraft;
        $data["slug"] = $model->slug;

        $operation = $client->updateItem(getenv("WEBFLOW_COLLECTION_ID"), $model->id, $data);

        $this->assertInstanceOf(ItemUpdatedOperation::class, $operation);
        $this->assertTrue($operation->wasSuccessful());
        $this->assertInstanceOf(ItemModel::class, $operation->getModel());

        // ...clean up
        $this->deleteItemModel($model);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Iterator
     * @covers Gitstore\Webflow\PaginatedIterator
     * @covers Gitstore\Webflow\Iterators\ItemsIterator
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetItems()
    {
        $client = $this->getClient();
        $model = $this->getItemModel();

        $items = $client->getItems(getenv("WEBFLOW_COLLECTION_ID"));

        $this->assertInstanceOf(ItemsIterator::class, $items);
        $this->assertIsInt($items->getRequestLimit());
        $this->assertIsInt($items->getRequestsRemaining());
        $this->assertIsInt($items->getCount());
        $this->assertIsInt($items->getLimit());
        $this->assertIsInt($items->getOffset());
        $this->assertIsInt($items->getTotal());

        $this->assertInstanceOf(ItemModel::class, $items[0]);

        // ...clean up
        $this->deleteItemModel($model);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\ItemModel
     * @covers Gitstore\Webflow\Response
     */
    public function testCanGetItem()
    {
        $client = $this->getClient();
        $model = $this->getItemModel();

        $item = $client->getItem(getenv("WEBFLOW_COLLECTION_ID"), $model->id);

        $this->assertInstanceOf(ItemModel::class, $item);
        $this->assertIsInt($item->getRequestLimit());
        $this->assertIsInt($item->getRequestsRemaining());

        $this->assertIsString($item->id);
        $this->assertIsString($item->slug);
        $this->assertIsBool($item->isDraft);
        $this->assertIsBool($item->isArchived);
        $this->assertInstanceOf(Carbon::class, $item->createdAt);
        $this->assertInstanceOf(Carbon::class, $item->updatedAt);
        $this->assertInstanceOf(Carbon::class, $item->publishedAt);

        // ...clean up
        $this->deleteItemModel($model);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemOperation
     * @covers Gitstore\Webflow\Operations\ItemDeletedOperation
     * @covers Gitstore\Webflow\Response
     */
    public function testCanHandleItemDeleteErrors()
    {
        $client = $this->getClient();
        $model = $this->getItemModel();

        $operation = $client->deleteItem(getenv("WEBFLOW_COLLECTION_ID"), "missing");

        $this->assertInstanceOf(ItemDeletedOperation::class, $operation);
        $this->assertFalse($operation->wasSuccessful());
        $this->assertIsString($operation->getData()["err"]);

        // ...clean up
        $this->deleteItemModel($model);
    }

    /**
     * @covers Gitstore\Webflow\Client
     * @covers Gitstore\Webflow\Clients\GuzzleClient
     * @covers Gitstore\Webflow\Model
     * @covers Gitstore\Webflow\Models\ItemModel
     * @covers Gitstore\Webflow\Response
     * @covers Gitstore\Webflow\Operation
     * @covers Gitstore\Webflow\Operations\ItemOperation
     * @covers Gitstore\Webflow\Operations\ItemDeletedOperation
     */
    public function testCanDeleteItem()
    {
        $client = $this->getClient();
        $model = $this->getItemModel();

        $operation = $client->deleteItem(getenv("WEBFLOW_COLLECTION_ID"), $model->id);

        $this->assertInstanceOf(ItemDeletedOperation::class, $operation);
        $this->assertTrue($operation->wasSuccessful());

        $this->expectException(ModelNotReturnedException::class);

        try {
            $operation->getModel();
        } catch (ModelNotReturnedException $e) {
            throw $e;
        } finally {
            // ...clean up
            $this->deleteItemModel($model);
        }
    }
}
