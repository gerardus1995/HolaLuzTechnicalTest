<?php

namespace App\Shared\Infrastructure\Input\Reader;

use App\Shared\Infrastructure\Exception\ErrorOpeningFileException;
use App\SuspiciousReadingDetector\Domain\Clients;

class CsvReader extends AbstractReader
{
    public function read(): Clients
    {
        $handle = $this->getHandle();
        $treatedClients = $this->getTreatedClients($handle);

        return $this->getClients($treatedClients);
    }

    /**
     * @throws ErrorOpeningFileException
     */
    private function getHandle()
    {
        $this->validateFile();

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new ErrorOpeningFileException($this->filePath);
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

            $treatedClients = $this->infoIntoTreatedClient(
                $treatedClients,
                $data[0],
                $data[1],
                $data[2]
            );
        }

        fclose($handle);

        return $treatedClients;
    }
}