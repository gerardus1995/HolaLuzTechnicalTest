<?php

namespace App\Shared\Infrastructure\Input\Reader;

use App\Shared\Infrastructure\Exception\FileNotFoundException;
use App\SuspiciousReadingDetector\Domain\Client;
use App\SuspiciousReadingDetector\Domain\Clients;
use App\SuspiciousReadingDetector\Domain\Reading;
use App\SuspiciousReadingDetector\Domain\Readings;
use App\SuspiciousReadingDetector\Domain\ValueObject\ClientId;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingPeriod;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingValue;

abstract class AbstractReader
{
    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    abstract public function read(): Clients;

    /**
     * @throws FileNotFoundException
     */
    protected function validateFile(): void
    {
        if (!file_exists($this->filePath) || !is_readable($this->filePath)) {
            throw new FileNotFoundException($this->filePath);
        }
    }

    protected function getClients(array $treatedClients): Clients
    {
        $clients = new Clients();

        foreach ($treatedClients as $clientId => $treatedReadings) {
            $client = new Client(new ClientId($clientId), new Readings());

            foreach ($treatedReadings as $reading) {
                $reading = new Reading(new ReadingPeriod($reading['period']), new ReadingValue($reading['reading']));
                $client->addReading($reading);
            }

            $clients->add($client);
        }

        return $clients;
    }
}