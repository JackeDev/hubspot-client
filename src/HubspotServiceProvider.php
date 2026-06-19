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
        
        if (!config()->has('logging.channels.hubspot')) {
            config()->set('logging.channels.hubspot', [
                'driver' => 'daily',
                'path'   => storage_path('logs/hubspot.log'),
                'level'  => 'error',
                'days'   => 14,
            ]);
        }

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
