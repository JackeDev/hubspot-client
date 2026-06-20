<?php

namespace Tambourine\HubspotClient\Services;

use Override;
use Tambourine\HubspotClient\DTOs\DealData;

class HubspotDealService extends HubspotEntityService
{
    protected function endpoint(): string
    {
        return '/deals';
    }

    #[Override]
    protected function buildProperties(array $properties): array
    {
        $data = new DealData(...$properties);

        return $data->toHubSpotProperties();
    }
}
