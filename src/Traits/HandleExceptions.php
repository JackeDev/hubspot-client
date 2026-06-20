<?php
namespace Tambourine\HubspotClient\Traits;

use Illuminate\Http\Client\Response;
use Tambourine\HubspotClient\Exceptions\AuthorizationException;
use Tambourine\HubspotClient\Exceptions\ResourceNotFoundException;
use Tambourine\HubspotClient\Exceptions\RateLimitException;
use Tambourine\HubspotClient\Exceptions\GenericHubspotException;
use Tambourine\HubspotClient\Exceptions\ValidationException;

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
        422 => [
            'class' => ValidationException::class,
            'message' => 'Missing or invalid properties',
        ],
        'default' => [
            'class' => GenericHubspotException::class,
            'message' => 'HubSpot API error'
        ]
    ];

    public function handleError(?Response $response = null, array $context = [], ?int $code = 400): void
    {
        $exception = self::EXCEPTIONS[$response?->status() ?? $code] ?? self::EXCEPTIONS['default'];
        $class = $exception['class'];
        throw new $class($exception['message'], $response?->status() ?? $code, $response?->json(), $context);
    }
}