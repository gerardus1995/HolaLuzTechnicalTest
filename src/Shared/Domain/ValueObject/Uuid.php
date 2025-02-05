<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidUuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid extends StringValueObject
{
    /**
     * @throws InvalidUuid
     */
    public function __construct(string $uuid)
    {
        $this->ensureUuidIsValid($uuid);
        parent::__construct($uuid);
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4()->__toString());
    }

    /**
     * @throws InvalidUuid
     */
    private function ensureUuidIsValid(string $uuid): void
    {
        if (!RamseyUuid::isValid($uuid)) {
            throw new InvalidUuid();
        }
    }

    public function equals(Uuid $id): bool
    {
        return ($id->__toString() === $this->__toString());
    }
}
