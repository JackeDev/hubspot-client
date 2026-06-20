<?php

namespace Tambourine\HubspotClient\Tests\Feature\Services;

use Illuminate\Support\Facades\Http;
use Tambourine\HubspotClient\Exceptions\GenericHubspotException;
use Tambourine\HubspotClient\Exceptions\RateLimitException;
use Tambourine\HubspotClient\Exceptions\ValidationException;
use Tambourine\HubspotClient\Services\HubspotContactService;
use Tambourine\HubspotClient\Tests\TestCase;

class HubspotContactServiceTest extends TestCase
{
    private array $validProperties = [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
    ];

    private array $hubspotResponse = [
        'id' => '101',
    ];

    public function test_creates_a_contact_and_returns_the_hubspot_response(): void
    {
        Http::fake(['*' => Http::response($this->hubspotResponse, 201)]);

        $result = app(HubspotContactService::class)->create($this->validProperties);

        $this->assertSame($this->hubspotResponse, $result);
    }

    public function test_sends_the_correct_endpoint_and_mapped_properties(): void
    {
        Http::fake(['*' => Http::response(['id' => '101'], 201)]);

        app(HubspotContactService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            $request->method() === 'POST' &&
            str_contains($request->url(), '/contacts') &&
            $request->data()['properties']['firstName'] === 'John' &&
            $request->data()['properties']['lastName'] === 'Doe' &&
            $request->data()['properties']['email'] === 'john@example.com'
        );
    }

    public function test_sends_the_auth_token_in_the_authorization_header(): void
    {
        Http::fake(['*' => Http::response(['id' => '101'], 201)]);

        app(HubspotContactService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            $request->hasHeader('Authorization', 'Bearer fake-test-token')
        );
    }

    public function test_omits_phone_from_payload_when_not_provided(): void
    {
        Http::fake(['*' => Http::response(['id' => '101'], 201)]);

        app(HubspotContactService::class)->create($this->validProperties);

        Http::assertSent(fn ($request) =>
            !array_key_exists('phone', $request->data()['properties'])
        );
    }

    public function test_includes_phone_in_payload_when_provided(): void
    {
        Http::fake(['*' => Http::response(['id' => '101'], 201)]);

        app(HubspotContactService::class)->create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john@example.com',
            'phone'      => '+1234567890',
        ]);

        Http::assertSent(fn ($request) =>
            $request->data()['properties']['phone'] === '+1234567890'
        );
    }

    public function test_throws_rate_limit_exception_on_429(): void
    {
        $this->expectException(RateLimitException::class);

        Http::fake(['*' => Http::response(['message' => 'Too many requests'], 429)]);

        app(HubspotContactService::class)->create($this->validProperties);
    }

    public function test_throws_generic_hubspot_exception_on_unexpected_server_error(): void
    {
        $this->expectException(GenericHubspotException::class);

        Http::fake(['*' => Http::response(['message' => 'Internal server error'], 500)]);

        app(HubspotContactService::class)->create($this->validProperties);
    }

    public function test_throws_validation_exception_when_required_contact_field_is_missing_from_input(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Missing or invalid properties');

        app(HubspotContactService::class)->create(['first_name' => 'John']);
    }
}
