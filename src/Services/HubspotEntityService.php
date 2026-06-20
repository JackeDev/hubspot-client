<?php

namespace Tambourine\HubspotClient\Services;

use Tambourine\HubspotClient\Contracts\HubspotEntityInterface;

abstract class HubspotEntityService extends HubspotClient implements HubspotEntityInterface
{
    abstract protected function endpoint(): string;
    abstract protected function buildProperties(array $properties): array;

    public function create(array $properties): array
    {
        $data = $this->buildProperties($properties);
        return $this->httpPost($this->endpoint(), ['properties' => $data]);
    }
}
