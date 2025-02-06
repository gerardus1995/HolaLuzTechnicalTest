<?php

namespace App\SuspiciousReadingDetector\Application\Detect\DTO;

use App\SuspiciousReadingDetector\Domain\SuspiciousReadings;

class ResponseSuspiciousReadingDetector
{
    public function __construct(
        private readonly SuspiciousReadings $suspiciousReadings
    ) {
    }

    public function suspiciousReadings(): SuspiciousReadings
    {
        return $this->suspiciousReadings;
    }
}