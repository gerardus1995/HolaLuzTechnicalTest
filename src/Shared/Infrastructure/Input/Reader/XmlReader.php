<?php

namespace App\Shared\Infrastructure\Input\Reader;

use App\Shared\Infrastructure\Exception\ErrorOpeningFileException;
use App\SuspiciousReadingDetector\Domain\Clients;
use SimpleXMLElement;

class XmlReader extends AbstractReader
{
    public function read(): Clients
    {
        $xml = $this->getXml();
        $treatedClients = $this->getTreatedClients($xml);

        return $this->getClients($treatedClients);
    }

    private function getXml(): SimpleXMLElement
    {
        $this->validateFile();

        $xml = simplexml_load_file($this->filePath);
        if ($xml === false) {
            throw new ErrorOpeningFileException($this->filePath);
        }

        return $xml;
    }

    private function getTreatedClients(SimpleXMLElement $xml): array
    {
        $treatedClients = [];

        foreach ($xml->reading as $data) {
            $treatedClients = $this->infoIntoTreatedClient(
                $treatedClients,
                $data['clientID'],
                $data['period'],
                $data
            );
        }

        return $treatedClients;
    }
}