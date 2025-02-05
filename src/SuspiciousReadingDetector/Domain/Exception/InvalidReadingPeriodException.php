<?php

namespace App\SuspiciousReadingDetector\Domain\Exception;


use InvalidArgumentException;

final class InvalidReadingPeriodException extends InvalidArgumentException
{
    public function __construct(string $period)
    {
        parent::__construct("Invalid period format. Expected YYYY-MM, got: $period");
    }
}
