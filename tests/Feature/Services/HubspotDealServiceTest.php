<?php

namespace Tambourine\HubspotClient\Tests\Feature\Services;

use Illuminate\Support\Facades\Http;
use Tambourine\HubspotClient\Exceptions\AuthorizationException;
use Tambourine\HubspotClient\Exceptions\GenericHubspotException;
use Tambourine\HubspotClient\Exceptions\RateLimitException;
use Tambourine\HubspotClient\Exceptions\ValidationException;
use Tambourine\HubspotClient\Services\HubspotDealService;
use Tambourine\HubspotClient\Tests\TestCase;

class HubspotDealServiceTest extends TestCase
{
    private array $validProperties = [
        'name'     => 'Enterprise Deal',
        'amount'   => 15000,
        'pipeline' => 'default',
        'stage'    => 'appointmentscheduled',
    ];

    private array $hubspotResponse = [
        'id'         => '201',
        'properties' => [
            'dealName' => 'Enterprise Deal',
            'amount'   => '15000',
            'pipeline' => 'default',
            'stage'    => 'appointmentscheduled',
        ],
    ];

    public function test_creates_a_deal_and_returns_the_hubspot_response(): void
    {
        Http::fake(['*' => Http::response($this->hubspotResponse, 201)]);

        $result = app(HubspotDealService::class)->create($this->validProperties);

        $this->assertSame($this->hubspotResponse, $result);
    }

    public function test_sends_the_correct_endpoint_and_mapped_properties(): void
    {
        Http::fake(['*' => Http::response(['id' => '201'], 201)]);

        app(HubspotDealService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            $request->method() === 'POST' &&
            str_contains($request->url(), '/deals') &&
            $request->data()['properties']['dealName'] === 'Enterprise Deal' &&
            $request->data()['properties']['amount'] === 15000 &&
            $request->data()['properties']['pipeline'] === 'default' &&
            $request->data()['properties']['stage'] === 'appointmentscheduled'
        );
    }

    public function test_sends_the_auth_token_in_the_authorization_header(): void
    {
        Http::fake(['*' => Http::response(['id' => '201'], 201)]);

        app(HubspotDealService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            $request->hasHeader('Authorization', 'Bearer fake-test-token')
        );
    }

    public function test_throws_rate_limit_exception_on_429(): void
    {
        $this->expectException(RateLimitException::class);

        Http::fake(['*' => Http::response(['message' => 'Too many requests'], 429)]);

        app(HubspotDealService::class)->create($this->validProperties);
    }

    public function test_throws_generic_hubspot_exception_on_unexpected_server_error(): void
    {
        $this->expectException(GenericHubspotException::class);

        Http::fake(['*' => Http::response(['message' => 'Internal server error'], 500)]);

        app(HubspotDealService::class)->create($this->validProperties);
    }

    public function test_throws_validation_exception_when_required_deal_field_is_missing_from_input(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Missing or invalid properties');

        app(HubspotDealService::class)->create(['name' => 'Enterprise Deal', 'amount' => 15000]);
    }
}
