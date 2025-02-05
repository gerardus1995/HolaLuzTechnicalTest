<?php

namespace App\SuspiciousReadingDetector\Domain;

use App\Shared\Domain\AggregateRoot;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingPeriod;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingValue;

class Reading extends AggregateRoot
{
    private const SUSPICIOUS_READING_THRESHOLD_PERCENTAGE = 50;

    public function __construct(
        private readonly ReadingPeriod $period,
        private readonly ReadingValue $value
    ) {
    }

    public function getPeriod(): ReadingPeriod
    {
        return $this->period;
    }

    public function getValue(): ReadingValue
    {
        return $this->value;
    }

    public function isSuspiciousReading(Client $client): bool
    {
        $median = $client->getMediaForReadings();

        $thresholdUpper = $median * (1 + (self::SUSPICIOUS_READING_THRESHOLD_PERCENTAGE/100));
        $thresholdLower = $median * (self::SUSPICIOUS_READING_THRESHOLD_PERCENTAGE/100);

        if ($this->getValue()->__toInt() > $thresholdUpper || $this->getValue()->__toInt() < $thresholdLower
        ) {
            return true;
        }

        return false;
    }

    public function __toArray(): array
    {
        return [
            'period' => $this->period->__toString(),
            'value' => $this->value->__toInt(),
        ];
    }
}