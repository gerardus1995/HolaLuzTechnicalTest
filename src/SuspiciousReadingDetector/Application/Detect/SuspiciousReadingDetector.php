<?php

namespace App\SuspiciousReadingDetector\Application\Detect;

use App\Shared\Domain\ValueObject\FloatValueObject;
use App\SuspiciousReadingDetector\Application\Detect\DTO\RequestSuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Application\Detect\DTO\ResponseSuspiciousReadingDetector;
use App\SuspiciousReadingDetector\Domain\Client;
use App\SuspiciousReadingDetector\Domain\Reading;
use App\SuspiciousReadingDetector\Domain\SuspiciousReading;
use App\SuspiciousReadingDetector\Domain\SuspiciousReadings;

class SuspiciousReadingDetector
{
    public function __invoke(RequestSuspiciousReadingDetector $request): ResponseSuspiciousReadingDetector
    {
        $suspiciousReadings = new SuspiciousReadings();

        /** @var Client $client */
        foreach ($request->clients() as $client) {
            $median = $client->getMediaForReadings();

            /** @var Reading $reading */
            foreach ($client->getReadings() as $reading) {
                if ($reading->isSuspiciousReading($client)) {
                    $suspiciousReadings[] = new SuspiciousReading(
                        $client,
                        $reading,
                        new FloatValueObject($median)
                    );
                }
            }
        }

        return new ResponseSuspiciousReadingDetector($suspiciousReadings);
    }
}