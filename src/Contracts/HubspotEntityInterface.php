<?php

namespace Tambourine\HubspotClient\Contracts;

interface HubspotEntityInterface
{
    public function create(array $properties): array;
}
