<?php

namespace Tambourine\HubspotClient\DTOs;

class AssociationData {

    public function __construct(
        public readonly int $contact_id,
        public readonly int $deal_id,
    ){}

    public function toHubSpotProperties(): array
    {
        return array_filter([
            'contact_id' => $this->contact_id,
            'deal_id' => $this->deal_id,
        ], fn ($value) => $value !== null);
    }
}