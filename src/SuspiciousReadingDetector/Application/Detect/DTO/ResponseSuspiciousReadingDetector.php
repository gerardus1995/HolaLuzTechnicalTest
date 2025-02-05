<?php

namespace App\SuspiciousReadingDetector\Application\Detect\DTO;

use App\SuspiciousReadingDetector\Domain\SuspiciousReadings;

readonly class ResponseSuspiciousReadingDetector
{
    public function __construct(
        private SuspiciousReadings $suspiciousReadings
    ) {
    }

    public function suspiciousReadings(): SuspiciousReadings
    {
        return $this->suspiciousReadings;
    }
}