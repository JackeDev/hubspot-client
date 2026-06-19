<?php

namespace Tambourine\HubspotClient;

use Illuminate\Support\ServiceProvider;
use Tambourine\HubspotClient\Services\HubspotContactService;
use Tambourine\HubspotClient\Services\HubspotDealService;
use Tambourine\HubspotClient\Services\HubspotService;

class HubspotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hubspot.php', 'hubspot');

        if (!config()->has('logging.channels.hubspot')) {
            config()->set('logging.channels.hubspot', [
                'driver' => 'daily',
                'path'   => storage_path('logs/hubspot.log'),
                'level'  => 'error',
                'days'   => 14,
            ]);
        }

        $this->app->singleton(HubspotContactService::class);
        $this->app->singleton(HubspotDealService::class);
        $this->app->singleton(HubspotService::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/hubspot.php' => config_path('hubspot.php'),
        ], 'hubspot-config');
    }
}
