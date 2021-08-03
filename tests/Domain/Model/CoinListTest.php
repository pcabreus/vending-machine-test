<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Money;
use PHPUnit\Framework\TestCase;

class CoinListTest extends TestCase
{
    public function testCoinList()
    {
        $coinList = CoinList::create();
        $coinList->addCoin(Coin::create(0.05));
        self::assertEquals(5, $coinList->getTotal()->getValue());

        $coinList->addCoins(Coin::create(0.05), 1);
        self::assertEquals(10, $coinList->getTotal()->getValue());

        $money = $coinList->diff(Money::create(0.01));
        self::assertEquals(0.09, $money->toFloat());
    }

    public function testAddCoinList()
    {
        $coinList = CoinList::create();
        self::assertEquals(0, $coinList->getTotal()->getValue());

        $coinList->addCoinList(CoinList::create());
        self::assertEquals(0, $coinList->getTotal()->getValue());

        $coinListExtra = CoinList::create();
        $coinListExtra->addCoins(Coin::create(0.05), 2);
        $coinList->addCoinList($coinListExtra);
        self::assertEquals(10, $coinList->getTotal()->getValue());
    }
}
