<?php

namespace Tambourine\HubspotClient\Services;

use Illuminate\Support\Facades\Log;
use Tambourine\HubspotClient\Exceptions\AuthorizationException;

abstract class HubspotAuth
{
    public function getToken(): string
    {
        return config('hubspot.token');
    }
}
