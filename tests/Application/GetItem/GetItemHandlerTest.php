<?php

namespace App\Tests\Application\GetItem;

use App\Application\GetItem\GetItem;
use App\Application\GetItem\GetItemHandler;
use App\Domain\Exceptions\InsufficientMoneyException;
use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Model\Money;
use App\Domain\Service\ProcessorInterface;
use PHPUnit\Framework\TestCase;

class GetItemHandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $change = CoinList::create();
        $processor
            ->expects(self::once())
            ->method('extractItem')
            ->willReturn($change);

        $handler = new GetItemHandler($processor);

        $coinList = CoinList::create();
        $coinList->addCoins(Coin::create(0.05), 1);
        $getItem = new GetItem($coinList, Item::create('SODA', 1, Money::create(0.05)));
        $result = $handler($getItem);

        self::assertEquals($change, $result);
    }

    public function testNotFoundItemException(): void
    {
        $this->expectException(NotFoundItemException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $change = CoinList::create();
        $processor
            ->expects(self::once())
            ->method('extractItem')
            ->willThrowException(new NotFoundItemException(''));

        $handler = new GetItemHandler($processor);

        $coinList = CoinList::create();
        $getItem = new GetItem($coinList, Item::create('SODA', 1, Money::create(0.05)));
        $handler($getItem);
    }

    public function testInsufficientMoneyException(): void
    {
        $this->expectException(InsufficientMoneyException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $change = CoinList::create();
        $processor
            ->expects(self::once())
            ->method('extractItem')
            ->willThrowException(new InsufficientMoneyException('', 0, 0));

        $handler = new GetItemHandler($processor);

        $coinList = CoinList::create();
        $getItem = new GetItem($coinList, Item::create('SODA', 1, Money::create(0.05)));
        $handler($getItem);
    }
}
