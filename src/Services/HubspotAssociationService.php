<?php

namespace Tambourine\HubspotClient\Services;

use Override;
use Tambourine\HubspotClient\DTOs\AssociationData;

class HubspotAssociationService extends HubspotEntityService
{
    protected function endpoint(): string
    {
        return '/associations';
    }

    #[Override]
    protected function buildProperties(array $properties): array
    {
        $data = new AssociationData(...$properties);

        return $data->toHubSpotProperties();
    }
}
