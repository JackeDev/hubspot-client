<?php
namespace Tambourine\HubspotClient\Traits;

use Illuminate\Http\Client\Response;
use Tambourine\HubspotClient\Exceptions\AuthorizationException;
use Tambourine\HubspotClient\Exceptions\ResourceNotFoundException;
use Tambourine\HubspotClient\Exceptions\RateLimitException;
use Tambourine\HubspotClient\Exceptions\GenericHubspotException;

trait HandleExceptions {

    const EXCEPTIONS = [
        401 => [
            'class' => AuthorizationException::class,
            'message' => 'HubSpot authentication failed'
        ],
        404 => [
            'class' => ResourceNotFoundException::class,
            'message' => 'HubSpot resource not found'
        ],
        429 => [
            'class' => RateLimitException::class,
            'message' => 'HubSpot rate limit exceeded',
        ],
        'default' => [
            'class' => GenericHubspotException::class,
            'message' => 'HubSpot API error'
        ]
    ];

    private function handleError(Response $response, array $context = []): void
    {
        $exception = self::EXCEPTIONS[$response->status()] ?? self::EXCEPTIONS['default'];
        $class = $exception['class'];
        throw new $class($exception['message'], $response->status(), $response->json(), $context);
    }
}