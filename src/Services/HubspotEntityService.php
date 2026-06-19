<?php

namespace Tambourine\HubspotClient\Services;

use Tambourine\HubspotClient\Contracts\HubspotEntityInterface;

abstract class HubspotEntityService extends HubspotClient implements HubspotEntityInterface
{
    abstract protected function endpoint(): string;

    public function get(string $id): array
    {
        return $this->httpGet("{$this->endpoint()}/{$id}");
    }

    public function create(array $properties): array
    {
        return $this->httpPost($this->endpoint(), ['properties' => $properties]);
    }

    public function update(string $id, array $properties): array
    {
        return $this->httpPatch("{$this->endpoint()}/{$id}", ['properties' => $properties]);
    }
}
