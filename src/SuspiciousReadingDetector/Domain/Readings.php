<?php

namespace App\SuspiciousReadingDetector\Domain;

use Doctrine\Common\Collections\ArrayCollection;

class Readings extends ArrayCollection
{
    public function __construct(Reading ...$readings)
    {
        parent::__construct($readings);
    }
}
