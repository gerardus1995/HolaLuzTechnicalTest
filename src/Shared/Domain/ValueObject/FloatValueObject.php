<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class FloatValueObject
{
    protected float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function __toFloat(): float
    {
        return $this->value ?? 0;
    }
}
