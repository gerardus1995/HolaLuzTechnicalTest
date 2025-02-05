<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

abstract class DomainEvent
{
    private string $eventId;
    private string $aggregateId;
    private array $data;
    private DateTimeImmutable $occurredOn;

    public function __construct(
        string $aggregateId,
        array $data = [],
        string $eventId = null,
        DateTimeImmutable $occurredOn = null
    ) {
        $this->aggregateId = $aggregateId;
        $this->data = $data;
        $this->occurredOn = $occurredOn ?: new DateTimeImmutable();
        $this->eventId = $eventId ?: Uuid::uuid4()->toString();
    }

    abstract public static function eventName(): string;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }
}
