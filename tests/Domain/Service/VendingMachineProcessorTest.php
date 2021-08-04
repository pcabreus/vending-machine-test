<?php

namespace App\Tests\Domain\Service;

use App\Domain\Exceptions\InsufficientMoneyException;
use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Model\Money;
use App\Domain\Service\VendingMachineProcessor;
use PHPUnit\Framework\TestCase;

class VendingMachineProcessorTest extends TestCase
{

    public function testFindItem(): void
    {
        $processor = new VendingMachineProcessor();

        $result = $processor->findItem('SODA');
        self::assertNull($result);

        $item = Item::create('SODA', 1, Money::create(0.05));
        $processor->setItem($item);
        $result = $processor->findItem('SODA');
        self::assertEquals($item, $result);

    }

    public function testExtractItem(): void
    {
        $processor = new VendingMachineProcessor();

        $item = Item::create('SODA', 1, Money::create(0.05));
        $processor->setItem($item);
        $coins = CoinList::create();
        $coins->addCoins(Coin::create(0.05), 1);
        $processor->setTotalCoins($coins);

        $set = CoinList::create();
        $set->addCoins(Coin::create(0.05), 1);
        $result = $processor->extractItem($item, $set);

        self::assertEquals(CoinList::create(), $result);
        self::assertEquals(0, $processor->findItem('SODA')->getCount());
        self::assertEquals(2, $processor->getTotalCoins()->getCount(Coin::create(0.05)));
    }

    public function testNotFoundItemException(): void
    {
        $this->expectException(NotFoundItemException::class);
        $processor = new VendingMachineProcessor();

        $item = Item::create('SODA', 0, Money::create(0.05));
        $processor->setItem($item);
        $coins = CoinList::create();
        $coins->addCoins(Coin::create(0.05), 1);

        $set = CoinList::create();
        $set->addCoins(Coin::create(0.05), 1);
        $processor->extractItem($item, $set);
    }
    public function testInsufficientMoneyException(): void
    {
        $this->expectException(InsufficientMoneyException::class);
        $processor = new VendingMachineProcessor();

        $item = Item::create('SODA', 1, Money::create(0.10));
        $processor->setItem($item);
        $coins = CoinList::create();
        $coins->addCoins(Coin::create(0.05), 1);

        $set = CoinList::create();
        $set->addCoins(Coin::create(0.05), 1);
        $processor->extractItem($item, $set);
    }
}
