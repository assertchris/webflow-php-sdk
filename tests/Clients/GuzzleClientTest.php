<?php

use Gitstore\Webflow\Clients\GuzzleClient;
use Gitstore\Webflow\Iterators\{Sites};
use Gitstore\Webflow\Models\{Site};

class GuzzleClientTest extends TestCase
{
    private function getClient()
    {
        return new GuzzleClient(getenv("WEBFLOW_TOKEN"));
    }

    public function testCanGetSites()
    {
        $client = $this->getClient();
        $sites = $client->getSites();

        $this->assertInstanceOf(Sites::class, $sites);
        $this->assertInstanceOf(Site::class, $sites[0]);
    }

    public function testCanGetASite()
    {
        $client = $this->getClient();
        $site = $client->getSite(getenv("WEBFLOW_SITE_ID"));

        $this->assertInstanceOf(Site::class, $site);
    }
}
