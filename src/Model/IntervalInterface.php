<?php

declare(strict_types=1);

namespace App\Model;

interface IntervalInterface
{
    public function getId(): int;

    public function getMin(): int;

    public function getMax(): int;
}
