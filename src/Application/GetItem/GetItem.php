<?php

namespace App\Application\GetItem;

use App\Domain\Model\CoinList;

final class GetItem
{
    private CoinList $coins;
    private string $item;

    public function __construct(CoinList $coins, string $item)
    {
        $this->coins = $coins;
        $this->item = $item;
    }

    public function getCoins(): CoinList
    {
        return $this->coins;
    }

    public function setCoins(CoinList $coins): self
    {
        $this->coins = $coins;

        return $this;
    }

    public function getItem(): string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }
}