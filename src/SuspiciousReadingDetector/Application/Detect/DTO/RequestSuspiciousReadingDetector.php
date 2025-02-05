<?php

namespace App\SuspiciousReadingDetector\Application\Detect\DTO;

use App\SuspiciousReadingDetector\Domain\Clients;

readonly class RequestSuspiciousReadingDetector
{
    public function __construct(
        private Clients $clients
    ) {
    }

    public function clients(): Clients
    {
        return $this->clients;
    }
}