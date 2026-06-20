<?php

namespace Tambourine\HubspotClient\DTOs;

class ContactData {

    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly ?string $phone = null,
    ){}

    public function toHubSpotProperties(): array
    {
        return array_filter([
            'firstname' => $this->first_name,
            'lastname' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone
        ], fn ($value) => $value !== null);
    }
}