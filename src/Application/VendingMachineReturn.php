<?php

namespace App\Application;

use App\Domain\Model\CoinList;
use App\Domain\Model\Item;

class VendingMachineReturn
{
    private CoinList $coins;
    protected Item $item;
}