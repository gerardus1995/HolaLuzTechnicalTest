<?php

namespace App\SuspiciousReadingDetector\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\NumberValueObject;

class SuspiciousReading extends AggregateRoot
{
    public function __construct(
        private readonly Client            $client,
        private readonly Reading           $reading,
        private readonly FloatValueObject  $median
    ) {
    }

    public function getClientId(): string
    {
        return $this->client->getId()->__toString();
    }

    public function getMonth(): string
    {
        return $this->reading->getPeriod()->getMonth()->__toInt();
    }

    public function getReadingValue(): float
    {
        return $this->reading->getValue()->__toInt();
    }

    public function getMedian(): float
    {
        return $this->median->__toFloat();
    }

    public function __toArray(): array
    {
        return [
            'client' => $this->client->__toArray(),
            'month' => $this->getMonth(),
            'reading' => $this->reading->__toArray(),
            'median' => $this->median->__toFloat()
        ];
    }
}