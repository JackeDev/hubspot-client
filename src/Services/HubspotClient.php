<?php

namespace Tambourine\HubspotClient\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Tambourine\HubspotClient\Services\HubspotAuth;

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
            'payload'  => $payload,
        ];

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
        }

        return $response;
    }
}
