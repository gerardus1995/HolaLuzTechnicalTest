<?php

namespace App\SuspiciousReadingDetector\Domain;

use Doctrine\Common\Collections\ArrayCollection;

class SuspiciousReadings extends ArrayCollection
{
    public function __construct(SuspiciousReading ...$suspiciousReadings)
    {
        parent::__construct($suspiciousReadings);
    }
}
