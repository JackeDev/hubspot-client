<?php

namespace Tambourine\HubspotClient\Tests\Unit\DTOs;

use PHPUnit\Framework\TestCase;
use Tambourine\HubspotClient\DTOs\DealData;
use TypeError;

class DealDataTest extends TestCase
{
    public function test_maps_all_properties_to_hubspot_format(): void
    {
        $data = new DealData(
            name: 'Enterprise Deal',
            amount: 15000,
            pipeline: 'default',
            stage: 'appointmentscheduled',
        );

        $this->assertSame([
            'dealname' => 'Enterprise Deal',
            'amount'   => 15000,
            'pipeline' => 'default',
            'stage'    => 'appointmentscheduled',
        ], $data->toHubSpotProperties());
    }

    public function test_throws_type_error_when_required_field_is_missing(): void
    {
        $this->expectException(TypeError::class);

        $incomplete = ['name' => 'Enterprise Deal', 'amount' => 15000, 'pipeline' => 'default'];
        new DealData(...$incomplete);
    }
}
