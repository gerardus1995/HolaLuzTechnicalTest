<?php

namespace App\SuspiciousReadingDetector\Domain;

use Doctrine\Common\Collections\ArrayCollection;

class Clients extends ArrayCollection
{
    public function __construct(Client ...$clients)
    {
        parent::__construct($clients);
    }
}
