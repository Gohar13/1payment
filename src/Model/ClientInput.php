<?php

declare(strict_types=1);

namespace App\Model;

class ClientInput
{
    public int $inputValue = 0;

    public function getInput(): int
    {
        return $this->inputValue;
    }

    public function setInput(int $inputValue): void
    {
        $this->inputValue = $inputValue;
    }
}
