<?php

namespace App\Tests\SuspiciousReadingDetector\Domain;

use App\SuspiciousReadingDetector\Domain\Client;
use App\SuspiciousReadingDetector\Domain\Reading;
use App\SuspiciousReadingDetector\Domain\Readings;
use App\SuspiciousReadingDetector\Domain\ValueObject\ClientId;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingValue;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGetId()
    {
        $clientId = $this->createMock(ClientId::class);
        $readings = $this->createMock(Readings::class);

        $client = new Client($clientId, $readings);

        $this->assertSame($clientId, $client->getId());
    }

    public function testGetReadings()
    {
        $clientId = $this->createMock(ClientId::class);
        $readings = $this->createMock(Readings::class);

        $client = new Client($clientId, $readings);

        $this->assertSame($readings, $client->getReadings());
    }

    public function testGetMediaForReadings()
    {
        $reading1 = $this->createMock(Reading::class);
        $reading2 = $this->createMock(Reading::class);
        $reading3 = $this->createMock(Reading::class);

        $reading1->method('getValue')->willReturn(new ReadingValue(10));
        $reading2->method('getValue')->willReturn(new ReadingValue(20));
        $reading3->method('getValue')->willReturn(new ReadingValue(30));

        $readings = $this->createMock(Readings::class);
        $readings->method('toArray')->willReturn([$reading1, $reading2, $reading3]);

        $clientId = $this->createMock(ClientId::class);
        $client = new Client($clientId, $readings);

        $this->assertEquals(20, $client->getMediaForReadings());
    }

    public function testGetMediaForReadingsWithEvenNumberOfValues()
    {
        $reading1 = $this->createMock(Reading::class);
        $reading2 = $this->createMock(Reading::class);
        $reading3 = $this->createMock(Reading::class);
        $reading4 = $this->createMock(Reading::class);

        $reading1->method('getValue')->willReturn(new ReadingValue(10));
        $reading2->method('getValue')->willReturn(new ReadingValue(20));
        $reading3->method('getValue')->willReturn(new ReadingValue(30));
        $reading4->method('getValue')->willReturn(new ReadingValue(40));

        $readings = $this->createMock(Readings::class);
        $readings->method('toArray')->willReturn([$reading1, $reading2, $reading3, $reading4]);

        $clientId = $this->createMock(ClientId::class);
        $client = new Client($clientId, $readings);

        $this->assertEquals(25, $client->getMediaForReadings());
    }

    public function testAddReading()
    {
        $reading = $this->createMock(Reading::class);
        $readings = $this->createMock(Readings::class);

        $readings->expects($this->once())->method('add')->with($reading);

        $clientId = $this->createMock(ClientId::class);
        $client = new Client($clientId, $readings);

        $client->addReading($reading);
    }

    public function testToArray()
    {
        $clientId = $this->createMock(ClientId::class);
        $clientId->method('__toString')->willReturn('12345');

        $readings = $this->createMock(Readings::class);
        $readings->method('toArray')->willReturn(['reading1', 'reading2']);

        $client = new Client($clientId, $readings);

        $expected = [
            'id' => '12345',
            'readings' => ['reading1', 'reading2'],
        ];

        $this->assertEquals($expected, $client->__toArray());
    }
}