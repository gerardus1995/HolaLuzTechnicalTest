<?php

namespace App\Tests\SuspiciousReadingDetector\Domain;

use App\SuspiciousReadingDetector\Domain\Client;
use App\SuspiciousReadingDetector\Domain\Reading;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingPeriod;
use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingValue;
use PHPUnit\Framework\TestCase;

class ReadingTest extends TestCase
{
    public function testGetPeriod()
    {
        $period = $this->createMock(ReadingPeriod::class);
        $value = $this->createMock(ReadingValue::class);

        $reading = new Reading($period, $value);

        $this->assertSame($period, $reading->getPeriod());
    }

    public function testGetValue()
    {
        $period = $this->createMock(ReadingPeriod::class);
        $value = $this->createMock(ReadingValue::class);

        $reading = new Reading($period, $value);

        $this->assertSame($value, $reading->getValue());
    }

    public function testIsSuspiciousReadingAboveThreshold()
    {
        $period = $this->createMock(ReadingPeriod::class);
        $value = $this->createMock(ReadingValue::class);
        $client = $this->createMock(Client::class);

        $client->method('getMediaForReadings')->willReturn(100.0);
        $value->method('__toInt')->willReturn(160);

        $reading = new Reading($period, $value);

        $this->assertTrue($reading->isSuspiciousReading($client));
    }

    public function testIsSuspiciousReadingBelowThreshold()
    {
        $period = $this->createMock(ReadingPeriod::class);
        $value = $this->createMock(ReadingValue::class);
        $client = $this->createMock(Client::class);

        $client->method('getMediaForReadings')->willReturn(100.0);
        $value->method('__toInt')->willReturn(40);

        $reading = new Reading($period, $value);

        $this->assertTrue($reading->isSuspiciousReading($client));
    }

    public function testIsNotSuspiciousReadingWithinThreshold()
    {
        $period = $this->createMock(ReadingPeriod::class);
        $value = $this->createMock(ReadingValue::class);
        $client = $this->createMock(Client::class);

        $client->method('getMediaForReadings')->willReturn(100.0);
        $value->method('__toInt')->willReturn(120);

        $reading = new Reading($period, $value);

        $this->assertFalse($reading->isSuspiciousReading($client));
    }

    public function testToArray()
    {
        $period = $this->createMock(ReadingPeriod::class);
        $value = $this->createMock(ReadingValue::class);

        $period->method('__toString')->willReturn('2024-02-05 12:00:00');
        $value->method('__toInt')->willReturn(150);

        $reading = new Reading($period, $value);

        $expected = [
            'period' => '2024-02-05 12:00:00',
            'value' => 150,
        ];

        $this->assertEquals($expected, $reading->__toArray());
    }
}