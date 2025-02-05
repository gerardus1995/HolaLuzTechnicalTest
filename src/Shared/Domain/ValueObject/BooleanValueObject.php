<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class BooleanValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function __toInt(): int
    {
        return (int)$this->value;
    }

    public function __toBool(): bool
    {
        return $this->value;
    }
}
