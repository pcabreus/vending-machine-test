<?php

namespace App\Tests\Application\UpdateItem;

use App\Application\GetItem\GetItem;
use App\Application\GetItem\GetItemHandler;
use App\Application\UpdateItem\UpdateItem;
use App\Application\UpdateItem\UpdateItemHandler;
use App\Domain\Exceptions\InsufficientMoneyException;
use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Model\Money;
use App\Domain\Service\ProcessorInterface;
use PHPUnit\Framework\TestCase;

class UpdateItemHandlerTest extends TestCase
{
    public function testSuccess(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $processor
            ->expects(self::once())
            ->method('findItem')
            ->willReturn(Item::create('SODA', 1, Money::create(0.05)));

        $processor
            ->expects(self::once())
            ->method('setItem');

        $handler = new UpdateItemHandler($processor);

        $coinList = CoinList::create();
        $coinList->addCoins(Coin::create(0.05), 1);
        $updateItem = new UpdateItem('SODA', 1);
        $handler($updateItem);
    }

    public function testNotFoundItemException(): void
    {
        $this->expectException(NotFoundItemException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $processor
            ->expects(self::once())
            ->method('findItem')
            ->willReturn(null);

        $handler = new UpdateItemHandler($processor);

        $coinList = CoinList::create();
        $coinList->addCoins(Coin::create(0.05), 1);
        $updateItem = new UpdateItem('SODA', 1);
        $handler($updateItem);
    }
}
