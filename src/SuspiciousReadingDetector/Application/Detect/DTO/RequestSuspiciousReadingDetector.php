<?php

namespace App\SuspiciousReadingDetector\Application\Detect\DTO;

use App\SuspiciousReadingDetector\Domain\Clients;

class RequestSuspiciousReadingDetector
{
    public function __construct(
        private readonly Clients $clients
    ) {
    }

    public function clients(): Clients
    {
        return $this->clients;
    }
}