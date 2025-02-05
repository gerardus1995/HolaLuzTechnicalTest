<?php

namespace App\Shared\Domain\ValueObject;

class StringValueObject
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __toLowercase(): string
    {
        return strtolower($this->value);
    }

    public function __toUppercase(): string
    {
        return strtoupper($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function sameAs(string $value): bool
    {
        return $this->value === $value;
    }

    public function different(string $value): bool
    {
        return $this->value !== $value;
    }
}