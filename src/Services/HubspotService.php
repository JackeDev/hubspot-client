<?php

namespace Tambourine\HubspotClient\Services;

class HubspotService
{
    public function __construct(
        public readonly HubspotContactService $contacts,
        public readonly HubspotDealService $deals,
    ) {}
}
