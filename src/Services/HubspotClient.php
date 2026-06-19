<?php

namespace Tambourine\HubspotClient\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Tambourine\HubspotClient\Traits\HandleExceptions;

abstract class HubspotClient extends HubspotAuth
{
    use HandleExceptions;

    protected function httpGet(string $uri, array $query = []): array
    {
        return $this->request('GET', $uri, $query)->json();
    }

    protected function httpPost(string $uri, array $payload = []): array
    {
        return $this->request('POST', $uri, $payload)->json();
    }

    protected function httpPatch(string $uri, array $payload = []): array
    {
        return $this->request('PATCH', $uri, $payload)->json();
    }

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

    private function request(
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
