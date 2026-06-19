<?php

namespace Tambourine\HubspotClient;

use Tambourine\HubspotClient\Contracts\HubspotServiceInterface;
use Illuminate\Support\ServiceProvider;
use Tambourine\HubspotClient\Services\HubspotServices;

class HubspotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hubspot.php', 'hubspot');

        $this->app->singleton(HubspotServiceInterface::class, HubspotServices::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/hubspot.php' =>
                config_path('hubspot.php'),
        ], 'hubspot-config');
    }
}
