<?php

namespace Tambourine\HubspotClient\Contracts;

interface HubspotEntityInterface
{
    public function get(string $id): array;
    public function create(array $properties): array;
    public function update(string $id, array $properties): array;
}
