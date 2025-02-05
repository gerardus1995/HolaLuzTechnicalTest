<?php

namespace App\Shared\Infrastructure\Input\Reader;

use App\SuspiciousReadingDetector\Domain\Clients;
use RuntimeException;

class CsvReader extends AbstractReader
{
    public function read(): Clients
    {
        $handle = $this->getHandle();
        $treatedClients = $this->getTreatedClients($handle);

        return $this->getClients($treatedClients);
    }

    private function getHandle()
    {
        $this->validateFile();

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new RuntimeException("Error opening file: {$this->filePath}");
        }

        return $handle;
    }

    private function getTreatedClients($handle): array
    {
        $treatedClients = [];
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) !== 3) {
                continue;
            }

            $treatedClients[$data[0]][] = [
                'period' => $data[1],
                'reading' => $data[2],
            ];
        }

        fclose($handle);

        return $treatedClients;
    }
}