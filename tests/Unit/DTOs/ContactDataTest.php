<?php

namespace Tambourine\HubspotClient\Tests\Unit\DTOs;

use PHPUnit\Framework\TestCase;
use Tambourine\HubspotClient\DTOs\ContactData;
use TypeError;

class ContactDataTest extends TestCase
{
    public function test_maps_all_properties_to_hubspot_format(): void
    {
        $data = new ContactData(
            first_name: 'John',
            last_name: 'Doe',
            email: 'john@example.com',
            phone: '+1234567890',
        );

        $this->assertSame([
            'firstName' => 'John',
            'lastName'  => 'Doe',
            'email'     => 'john@example.com',
            'phone'     => '+1234567890',
        ], $data->toHubSpotProperties());
    }

    public function test_excludes_phone_when_not_provided(): void
    {
        $data = new ContactData(
            first_name: 'John',
            last_name: 'Doe',
            email: 'john@example.com',
        );

        $properties = $data->toHubSpotProperties();

        $this->assertArrayHasKey('firstName', $properties);
        $this->assertArrayHasKey('lastName', $properties);
        $this->assertArrayHasKey('email', $properties);
        $this->assertArrayNotHasKey('phone', $properties);
    }

    public function test_throws_type_error_when_required_field_is_missing(): void
    {
        $this->expectException(TypeError::class);

        $incomplete = ['first_name' => 'John', 'last_name' => 'Doe'];
        new ContactData(...$incomplete);
    }
}
