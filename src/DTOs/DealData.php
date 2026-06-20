<?php

namespace Tambourine\HubspotClient\DTOs;

class DealData {

    public function __construct(
        public readonly string $name,
        public readonly int $amount,
        public readonly string $pipeline,
        public readonly string $stage,
    ){}

    public function toHubSpotProperties(): array
    {
        return array_filter([
            'dealname' => $this->name,
            'amount' => $this->amount,
            'pipeline' => $this->pipeline,
            'stage' => $this->stage
        ], fn ($value) => $value !== null);
    }
}