<?php

namespace App\Tests\SuspiciousReadingDetector\Domain\ValueObject;

use App\SuspiciousReadingDetector\Domain\ValueObject\ReadingPeriod;
use App\Shared\Domain\ValueObject\NumberValueObject;
use App\SuspiciousReadingDetector\Domain\Exception\InvalidReadingPeriodException;
use PHPUnit\Framework\TestCase;

class ReadingPeriodTest extends TestCase
{
    public function testValidReadingPeriod()
    {
        $period = new ReadingPeriod('2024-02');

        $this->assertInstanceOf(ReadingPeriod::class, $period);
        $this->assertEquals(2024, $period->getYear()->__toInt());
        $this->assertEquals(2, $period->getMonth()->__toInt());
        $this->assertEquals('2024-02', $period->__toString());
    }

    public function testInvalidReadingPeriodThrowsException()
    {
        $this->expectException(InvalidReadingPeriodException::class);
        new ReadingPeriod('2024-13');
    }

    public function testInvalidFormatThrowsException()
    {
        $this->expectException(InvalidReadingPeriodException::class);
        new ReadingPeriod('24-02');
    }

    public function testGetYear()
    {
        $period = new ReadingPeriod('2023-05');
        $this->assertInstanceOf(NumberValueObject::class, $period->getYear());
        $this->assertEquals(2023, $period->getYear()->__toInt());
    }

    public function testGetMonth()
    {
        $period = new ReadingPeriod('2023-05');
        $this->assertInstanceOf(NumberValueObject::class, $period->getMonth());
        $this->assertEquals(5, $period->getMonth()->__toInt());
    }

    public function testToString()
    {
        $period = new ReadingPeriod('2025-12');
        $this->assertEquals('2025-12', $period->__toString());
    }
}