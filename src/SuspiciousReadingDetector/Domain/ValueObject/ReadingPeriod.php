<?php

declare(strict_types=1);

namespace App\SuspiciousReadingDetector\Domain\ValueObject;

use App\Shared\Domain\ValueObject\NumberValueObject;
use App\SuspiciousReadingDetector\Domain\Exception\InvalidReadingPeriodException;

class ReadingPeriod
{
    private NumberValueObject $year;
    private NumberValueObject $month;

    public function __construct(string $period)
    {
        $matches = $this->complainsWithFormat($period);

        $this->year = new NumberValueObject((int) $matches[1]);
        $this->month = new NumberValueObject((int) $matches[2]);
    }

    public function getYear(): NumberValueObject
    {
        return $this->year;
    }

    public function getMonth(): NumberValueObject
    {
        return $this->month;
    }

    public function __toString(): string
    {
        return sprintf('%04d-%02d', $this->year->__toInt(), $this->month->__toInt());
    }

    private function complainsWithFormat(string $period): array
    {
        if (!preg_match('/^(20\d{2})-(0[1-9]|1[0-2])$/', $period, $matches)) {
            throw new InvalidReadingPeriodException($period);
        }

        return $matches;
    }
}