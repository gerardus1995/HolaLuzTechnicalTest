<?php

namespace App\Tests\SuspiciousReadingDetector\Application\Detect;

use App\SuspiciousReadingDetector\Application\Detect\DTO\RequestSuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Application\Detect\DTO\ResponseSuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Application\Detect\SuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Domain\Client;
use App\SuspiciousReadingDetector\Domain\Clients;
use App\SuspiciousReadingDetector\Domain\Reading;
use App\SuspiciousReadingDetector\Domain\Readings;
use App\SuspiciousReadingDetector\Domain\SuspiciousReading;
use App\SuspiciousReadingDetector\Domain\SuspiciousReadings;
use PHPUnit\Framework\TestCase;

class SuspiciousReadingDetectorTest extends TestCase
{
    private SuspiciousReadingDetector $suspiciousReadingDetector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->suspiciousReadingDetector = new SuspiciousReadingDetector();
    }

    public function testDetectSuspiciousReadings(): void
    {
        $reading1 = $this->createMock(Reading::class);
        $reading2 = $this->createMock(Reading::class);

        $reading1->method('isSuspiciousReading')->willReturn(true);
        $reading2->method('isSuspiciousReading')->willReturn(false);

        $client = $this->createMock(Client::class);
        $client->method('getReadings')->willReturn(new Readings($reading1, $reading2));
        $client->method('getMediaForReadings')->willReturn(50.0);

        $request = $this->createMock(RequestSuspiciousReadingDetector::class);
        $request->method('clients')->willReturn(new Clients($client));

        $response = $this->suspiciousReadingDetector->__invoke($request);

        $this->assertInstanceOf(ResponseSuspiciousReadingDetector::class, $response);
        $suspiciousReadings = $response->suspiciousReadings();

        $this->assertInstanceOf(SuspiciousReadings::class, $suspiciousReadings);
        $this->assertCount(1, $suspiciousReadings);

        $firstSuspiciousReading = $suspiciousReadings[0];
        $this->assertInstanceOf(SuspiciousReading::class, $firstSuspiciousReading);
        $this->assertEquals(50.0, $firstSuspiciousReading->getMedian());
    }

    public function testDetectSuspiciousReadingsWithNoClients()
    {
        $request = $this->createMock(RequestSuspiciousReadingDetector::class);
        $request->method('clients')->willReturn(new Clients());

        $response = $this->suspiciousReadingDetector->__invoke($request);

        $this->assertInstanceOf(ResponseSuspiciousReadingDetector::class, $response);
        $this->assertCount(0, $response->suspiciousReadings());
    }

    public function testDetectSuspiciousReadingsWithClientHavingNoReadings()
    {
        $client = $this->createMock(Client::class);
        $client->method('getReadings')->willReturn(new Readings());
        $client->method('getMediaForReadings')->willReturn(0.0);

        $request = $this->createMock(RequestSuspiciousReadingDetector::class);
        $request->method('clients')->willReturn(new Clients($client));

        $detector = new SuspiciousReadingDetector();
        $response = $this->suspiciousReadingDetector->__invoke($request);

        $this->assertInstanceOf(ResponseSuspiciousReadingDetector::class, $response);
        $this->assertCount(0, $response->suspiciousReadings());
    }
}
