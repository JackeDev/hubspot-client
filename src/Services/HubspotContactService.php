<?php

namespace Tambourine\HubspotClient\Services;

use Override;
use Tambourine\HubspotClient\DTOs\ContactData;

class HubspotContactService extends HubspotEntityService
{
    protected function endpoint(): string
    {
        return '/contacts';
    }

    #[Override]
    protected function buildProperties(array $properties): array
    {
        $data = new ContactData(...$properties);

        return $data->toHubSpotProperties();
    }
}
