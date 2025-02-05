<?php

namespace App\SuspiciousReadingDetector\Domain;

use App\Shared\Domain\AggregateRoot;
use App\SuspiciousReadingDetector\Domain\ValueObject\ClientId;

class Client extends AggregateRoot
{
    public function __construct(
        private readonly ClientId $id,
        private readonly Readings $readings
    ) {
    }

    public function getId(): ClientId
    {
        return $this->id;
    }

    public function getReadings(): Readings
    {
        return $this->readings;
    }

    public function getMediaForReadings(): float
    {
        $readings = $this->getReadings()->toArray();
        $values = array_map(fn(Reading $r) => $r->getValue(), $readings);

        return  $this->calculateMedian($values);
    }

    private function calculateMedian(array $values): float
    {
        sort($values);
        $count = count($values);
        $middle = intdiv($count, 2);

        if ($count % 2 === 0) {
            return ($values[$middle - 1]->__toInt() + $values[$middle]->__toInt()) / 2;
        }

        return $values[$middle];
    }

    public function addReading(Reading $reading): void
    {
        $this->readings->add($reading);
    }

    public function __toArray(): array
    {
        return [
            'id' => $this->id->__toString(),
            'readings' => $this->readings->toArray(),
        ];
    }
}