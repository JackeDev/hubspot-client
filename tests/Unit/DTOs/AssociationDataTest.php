<?php

namespace Tambourine\HubspotClient\Tests\Unit\DTOs;

use PHPUnit\Framework\TestCase;
use Tambourine\HubspotClient\DTOs\AssociationData;
use TypeError;

class AssociationDataTest extends TestCase
{
    public function test_maps_all_properties_to_hubspot_format(): void
    {
        $data = new AssociationData(
            contact_id: 12345,
            deal_id: 98765,
        );

        $this->assertSame([
            'contact_id' => 12345,
            'deal_id'=> 98765,
        ], $data->toHubSpotProperties());
    }

    public function test_throws_type_error_when_required_field_is_missing(): void
    {
        $this->expectException(TypeError::class);

        $incomplete = ['contact_id' => 12345];
        new AssociationData(...$incomplete);
    }
}
