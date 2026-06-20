<?php

namespace Tambourine\HubspotClient\Services;

use Tambourine\HubspotClient\Contracts\HubspotEntityInterface;
use TypeError;
use Illuminate\Http\Client\Response;

abstract class HubspotEntityService extends HubspotClient implements HubspotEntityInterface
{
    abstract protected function endpoint(): string;
    abstract protected function buildProperties(array $properties): array;

    public function create(array $properties): Response
    {
        try {
            $data = $this->buildProperties($properties);
        } catch (TypeError) {
            $this->handleError(context: ['provided' => array_keys($properties)], code: 422);
        }

        return $this->httpPost($this->endpoint(), ['properties' => $data ?? []]);
    }
}
