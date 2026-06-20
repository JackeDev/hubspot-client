<?php

namespace Tambourine\HubspotClient\Contracts;

use Illuminate\Http\Client\Response;

interface HubspotEntityInterface
{
    public function create(array $properties): Response;
}
