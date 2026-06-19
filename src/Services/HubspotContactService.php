<?php

namespace Tambourine\HubspotClient\Services;

class HubspotContactService extends HubspotEntityService
{
    protected function endpoint(): string
    {
        return '/contacts';
    }
}
