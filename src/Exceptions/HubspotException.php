<?php

namespace Tambourine\HubspotClient\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

abstract class HubspotException extends Exception
{
    public function __construct(
        string $message = 'Hubspot request failed',
        int $code = 0,
        protected ?array $response = null,
        protected ?array $context = []
    ) {
        parent::__construct($message, $code);
        Log::channel('hubspot')->error(
            $message,
            $context
        );
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

}
