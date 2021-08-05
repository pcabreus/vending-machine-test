<?php

namespace App\Application\GetItem;

final class GetItem
{
    private array $coins;
    private string $selector;

    public function __construct(array $coins, string $selector)
    {
        $this->coins = $coins;
        $this->selector = $selector;
    }

    public function getCoins(): array
    {
        return $this->coins;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }
}