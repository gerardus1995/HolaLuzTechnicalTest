<?php

namespace App\Shared\Infrastructure\Exception;

use RuntimeException;

final class ErrorOpeningFileException extends RuntimeException
{
    public function __construct(string $fileName)
    {
        parent::__construct("Error opening file: $fileName");
    }
}
