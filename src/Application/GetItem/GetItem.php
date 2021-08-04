<?php

namespace App\Application\GetItem;

use App\Domain\Model\CoinList;
use App\Domain\Model\Item;

final class GetItem
{
    private CoinList $coins;
    private Item $item;

    public function __construct(CoinList $coins, Item $item)
    {
        $this->coins = $coins;
        $this->item = $item;
    }

    public function getCoins(): CoinList
    {
        return $this->coins;
    }

    public function getItem(): Item
    {
        return $this->item;
    }
}