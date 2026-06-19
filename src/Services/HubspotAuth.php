<?php

namespace Tambourine\HubspotClient\Services;

use Illuminate\Support\Facades\Log;
use Tambourine\HubspotClient\Exceptions\HubspotException;

abstract class HubspotAuth
{
    public function getToken(): string
    {
        return config('hubspot.token');
    }

    public function handleExpiredToken(array $context): void
    {
        Log::channel('hubspot')->warning(
            'HubSpot token expired or unauthorized. Manual token replacement required.',
            $context
        );
        throw new HubspotException('HubSpot token expired or unauthorized. Manual token replacement required.', 401);
    }
}
