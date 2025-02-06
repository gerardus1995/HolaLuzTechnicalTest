<?php

namespace App\Tests\SuspiciousReadingDetector\Domain;

use App\Shared\Domain\ValueObject\NumberValueObject;
use App\SuspiciousReadingDetector\Domain\Client;
use App\SuspiciousReadingDetector\Domain\Reading;
use App\SuspiciousReadingDetector\Domain\SuspiciousReading;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\SuspiciousReadingDetector\Domain\ValueObject\ClientId;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingPeriod;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingValue;
use PHPUnit\Framework\TestCase;

class SuspiciousReadingTest extends TestCase
{
    public function testGetClientId()
    {
        $client = $this->createMock(Client::class);
        $reading = $this->createMock(Reading::class);
        $median = $this->createMock(FloatValueObject::class);

        $client->method('getId')->willReturn($this->mockClientId('12345'));

        $suspiciousReading = new SuspiciousReading($client, $reading, $median);

        $this->assertEquals('12345', $suspiciousReading->getClientId());
    }

    public function testGetMonth()
    {
        $client = $this->createMock(Client::class);
        $reading = $this->createMock(Reading::class);
        $median = $this->createMock(FloatValueObject::class);

        $monthMock = new NumberValueObject(2);

        $readingPeriodMock = $this->createMock(ReadingPeriod::class);
        $readingPeriodMock->method('getMonth')->willReturn($monthMock);

        $reading->method('getPeriod')->willReturn($readingPeriodMock);

        $suspiciousReading = new SuspiciousReading($client, $reading, $median);

        $this->assertEquals(2, $suspiciousReading->getMonth());
    }

    public function testGetReadingValue()
    {
        $client = $this->createMock(Client::class);
        $reading = $this->createMock(Reading::class);
        $median = $this->createMock(FloatValueObject::class);

        $readingValueMock = $this->createMock(ReadingValue::class);
        $readingValueMock->method('__toInt')->willReturn(120);

        $reading->method('getValue')->willReturn($readingValueMock);

        $suspiciousReading = new SuspiciousReading($client, $reading, $median);

        $this->assertEquals(120, $suspiciousReading->getReadingValue());
    }

    public function testGetMedian()
    {
        $client = $this->createMock(Client::class);
        $reading = $this->createMock(Reading::class);
        $median = $this->createMock(FloatValueObject::class);

        $median->method('__toFloat')->willReturn(100.5);

        $suspiciousReading = new SuspiciousReading($client, $reading, $median);

        $this->assertEquals(100.5, $suspiciousReading->getMedian());
    }

    public function testToArray()
    {
        $client = $this->createMock(Client::class);
        $reading = $this->createMock(Reading::class);
        $median = $this->createMock(FloatValueObject::class);

        $client->method('__toArray')->willReturn(['id' => '12345']);
        $reading->method('__toArray')->willReturn([
            'period' => '2024-02-05 12:00:00',
            'value' => 120
        ]);
        $median->method('__toFloat')->willReturn(100.5);

        $monthMock = new NumberValueObject(2);

        $readingPeriodMock = $this->createMock(ReadingPeriod::class);
        $readingPeriodMock->method('getMonth')->willReturn($monthMock);

        $reading->method('getPeriod')->willReturn($readingPeriodMock);

        $suspiciousReading = new SuspiciousReading($client, $reading, $median);

        $expected = [
            'client' => ['id' => '12345'],
            'month' => 2,
            'reading' => [
                'period' => '2024-02-05 12:00:00',
                'value' => 120
            ],
            'median' => 100.5
        ];

        $this->assertEquals($expected, $suspiciousReading->__toArray());
    }

    private function mockClientId(string $id): ClientId
    {
        $mock = $this->createMock(ClientId::class);
        $mock->method('__toString')->willReturn($id);

        return $mock;
    }
}