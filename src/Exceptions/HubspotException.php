<?php

namespace App\Exceptions;

use Exception;

class HubspotException extends Exception
{
    public function __construct(
        string $message = 'Hubspot request failed',
        int $code = 0,
        protected ?array $response = null
    ) {
        parent::__construct($message, $code);
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

}
