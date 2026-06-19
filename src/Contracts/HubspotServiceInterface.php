<?php

namespace Tambourine\HubspotClient\Contracts;

interface HubspotServiceInterface
{
    public function createContact(array $data): array;
}
