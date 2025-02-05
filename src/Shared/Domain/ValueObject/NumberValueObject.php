<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class NumberValueObject
{
    protected int $value;

    public function __construct(int $value = 0)
    {
        $this->value = $value;
    }

    public function __toInt(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return "$this->value";
    }
}
