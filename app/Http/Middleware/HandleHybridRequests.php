<?php

namespace App\Http\Middleware;

use Hybridly\Http\Middleware;

class HandleHybridRequests extends Middleware
{
    /**
     * Defines the properties that are shared to all requests.
     */
    public function share(): array
    {
        return [];
    }
}
