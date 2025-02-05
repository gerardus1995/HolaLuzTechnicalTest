<?php

namespace App\Shared\Infrastructure\Input\Reader;

use App\SuspiciousReadingDetector\Domain\Clients;
use RuntimeException;
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
            throw new RuntimeException("Error parsing XML file: {$this->filePath}");
        }

        return $xml;
    }

    private function getTreatedClients(SimpleXMLElement $xml): array
    {
        $treatedClients = [];
        foreach ($xml->reading as $data) {
            $treatedClients[(string) $data['clientID']][] = [
                'period' => (string) $data['period'],
                'reading' => (float) $data,
            ];
        }

        return $treatedClients;
    }
}