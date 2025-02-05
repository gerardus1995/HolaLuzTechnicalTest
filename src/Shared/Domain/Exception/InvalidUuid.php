<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;
use Exception;

final class InvalidUuid extends DomainException
{
    public function __construct($code = 0, Exception $previous = null)
    {
        parent::__construct('Invalid uuid', $code, $previous);
    }
}
