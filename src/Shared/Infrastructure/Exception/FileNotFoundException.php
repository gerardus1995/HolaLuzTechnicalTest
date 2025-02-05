<?php

namespace App\Shared\Infrastructure\Exception;

use RuntimeException;

final class FileNotFoundException extends RuntimeException
{
    public function __construct(string $fileName)
    {
        parent::__construct("File with name: $fileName was not found");
    }
}
