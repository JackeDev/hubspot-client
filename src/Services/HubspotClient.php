<?php

namespace Tambourine\HubspotClient\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Tambourine\HubspotClient\Services\HubspotAuth;
use Tambourine\HubspotClient\Traits\HandleExceptions;

abstract class HubspotClient extends HubspotAuth
{
    use HandleExceptions;

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

        return $response;
    }
}
