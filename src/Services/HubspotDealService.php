<?php

namespace Tambourine\HubspotClient\Services;

class HubspotDealService extends HubspotEntityService
{
    protected function endpoint(): string
    {
        return '/objects/deals';
    }
}
