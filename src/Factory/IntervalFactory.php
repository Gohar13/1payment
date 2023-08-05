<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Interval;
use App\Model\IntervalInterface;

class IntervalFactory
{
    public function create(
        int $min,
        int $max
    ): IntervalInterface
    {
        $interval = new Interval();
        $interval
            ->setMin($min)
            ->setMax($max);

        return $interval;
    }

}