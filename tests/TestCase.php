<?php

namespace Tambourine\HubspotClient\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tambourine\HubspotClient\HubspotServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [HubspotServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('hubspot.token', 'fake-test-token');
        $app['config']->set('hubspot.base_url', 'https://api.hubapi.com/crm/v3');
        $app['config']->set('logging.channels.hubspot', ['driver' => 'null']);
    }
}
