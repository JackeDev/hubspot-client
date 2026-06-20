<?php

namespace Tambourine\HubspotClient\Tests\Feature\Services;

use Illuminate\Support\Facades\Http;
use Tambourine\HubspotClient\Exceptions\GenericHubspotException;
use Tambourine\HubspotClient\Exceptions\RateLimitException;
use Tambourine\HubspotClient\Exceptions\ResourceNotFoundException;
use Tambourine\HubspotClient\Exceptions\ValidationException;
use Tambourine\HubspotClient\Services\HubspotAssociationService;
use Tambourine\HubspotClient\Tests\TestCase;

class HubspotAssociationServiceTest extends TestCase
{
    private array $validProperties = [
        'contact_id' => 12345,
        'deal_id'=> 98765,
    ];

    private $hubspotResponse = [
        'id' => '201'
    ];

    public function test_creates_a_association_and_returns_the_hubspot_response(): void
    {
        Http::fake(['*' => Http::response($this->hubspotResponse, 201)]);

        $result = app(HubspotAssociationService::class)->create($this->validProperties);

        $this->assertSame($this->hubspotResponse, $result->json());
    }

    public function test_sends_the_correct_endpoint_and_mapped_properties(): void
    {
        Http::fake(['*' => Http::response(['id' => '201'], 201)]);

        app(HubspotAssociationService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            $request->method() === 'POST' &&
            str_contains($request->url(), '/associations') &&
            $request->data()['properties']['contact_id'] === 12345 &&
            $request->data()['properties']['deal_id'] === 98765
        );
    }

    public function test_sends_the_auth_token_in_the_authorization_header(): void
    {
        Http::fake(['*' => Http::response(['id' => '201'], 201)]);

        app(HubspotAssociationService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            $request->hasHeader('Authorization', 'Bearer fake-test-token')
        );
    }

    public function test_throws_rate_limit_exception_on_429(): void
    {
        $this->expectException(RateLimitException::class);

        Http::fake(['*' => Http::response(['message' => 'Too many requests'], 429)]);

        app(HubspotAssociationService::class)->create($this->validProperties);
    }

    public function test_throws_generic_hubspot_exception_on_unexpected_server_error(): void
    {
        $this->expectException(GenericHubspotException::class);

        Http::fake(['*' => Http::response(['message' => 'Internal server error'], 500)]);

        app(HubspotAssociationService::class)->create($this->validProperties);
    }

    public function test_throws_resource_not_found_exception_when_contact_id_does_not_exist(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        Http::fake(['*' => Http::response(['message' => 'Contact not found'], 404)]);

        app(HubspotAssociationService::class)->create([
            'contact_id' => 99999999,
            'deal_id'    => 98765,
        ]);
    }

    public function test_throws_resource_not_found_exception_when_deal_id_does_not_exist(): void
    {
        $this->expectException(ResourceNotFoundException::class);

        Http::fake(['*' => Http::response(['message' => 'Deal not found'], 404)]);

        app(HubspotAssociationService::class)->create([
            'contact_id' => 12345,
            'deal_id'    => 99999999,
        ]);
    }

    public function test_throws_validation_exception_when_required_deal_field_is_missing_from_input(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Missing or invalid properties');

        app(HubspotAssociationService::class)->create(['contact_id' => 12345]);
    }
}
