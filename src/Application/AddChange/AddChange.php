<?php

namespace App\Application\AddChange;

class AddChange
{
    private array $coins;

    public function __construct(array $coins)
    {
        $this->coins = $coins;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }
}