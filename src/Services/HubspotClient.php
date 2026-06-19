<?php

namespace Tambourine\HubspotClient\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Tambourine\HubspotClient\Services\HubspotAuth;
use Tambourine\HubspotClient\Exceptions\AuthorizationException;
use Tambourine\HubspotClient\Exceptions\GenericHubspotException;
use Tambourine\HubspotClient\Exceptions\RateLimitException;
use Tambourine\HubspotClient\Exceptions\ValidationException;
use Tambourine\HubspotClient\Exceptions\ResourceNotFoundException;

abstract class HubspotClient extends HubspotAuth
{
    private function getApiUrl(): string
    {
        return config('hubspot.base_url');
    }

    private function client()
    {
        return Http::baseUrl($this->getApiUrl())
            ->withToken($this->getToken())
            ->acceptJson()
            ->contentType('application/json')
            ->timeout(15);
    }

    protected function request(
        string $method,
        string $endpoint,
        array $payload = []
    ): Response {
        
        $response = $this->client()->send($method, $endpoint, ['json' => $payload]);
    
        $context = [
            'endpoint' => $endpoint,
            'method'   => $method,
            'status'   => $response->status(),
            'payload'  => $payload,
            'body'     => $response->json(),
        ];

        if ($response->failed()) {
            $this->handleError($response, $context);
        }
        /*
        if ($response->unauthorized()) {
            $this->handleExpiredToken($context);
        }

        if ($response->failed()) {
            Log::channel('hubspot')->error('HubSpot request failed', [
                'endpoint' => $endpoint,
                'status'   => $response->status(),
                'body'     => $response->json(),
            ]);
            throw new \Exception('HubSpot request failed');
        }*/

        return $response;
    }

    private function handleError(Response $response, array $context = []): void
    {
        match ($response->status()) {
            401 => throw new AuthorizationException(
                message: 'HubSpot authentication failed',
                context: $context
            ),

            404 => throw new ResourceNotFoundException(
                message: 'HubSpot resource not found',
                context: $context
            ),

            429 => throw new RateLimitException(
                message: 'HubSpot rate limit exceeded',
                context: $context
            ),

            default => throw new GenericHubSpotException(
                message: $response->json('message') ?? 'HubSpot API error',
                context: $context
            ),
        };
    }
}
