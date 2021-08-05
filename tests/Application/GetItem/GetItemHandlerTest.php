<?php

namespace App\Tests\Application\GetItem;

use App\Application\GetItem\GetItem;
use App\Application\GetItem\GetItemHandler;
use App\Domain\Exceptions\InsufficientMoneyException;
use App\Domain\Exceptions\InvalidCoinException;
use App\Domain\Exceptions\NotFoundItemException;
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

        $processor
            ->expects(self::once())
            ->method('findItem')
            ->with('SODA')
            ->willReturn(Item::create('SODA', 1, Money::create(0.05)));

        $handler = new GetItemHandler($processor);


        $getItem = new GetItem([0.05], 'SODA');
        $result = $handler($getItem);

        self::assertEquals($change, $result);
    }

    public function testNotFoundItemException(): void
    {
        $this->expectException(NotFoundItemException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $processor
            ->expects(self::once())
            ->method('findItem')
            ->willReturn(null);

        $handler = new GetItemHandler($processor);
        $getItem = new GetItem([], 'COCA-COLA');
        $handler($getItem);
    }

    public function testInvalidCoinException(): void
    {
        $this->expectException(InvalidCoinException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $processor
            ->expects(self::once())
            ->method('findItem')
            ->with('SODA')
            ->willReturn(Item::create('SODA', 1, Money::create(0.05)));

        $handler = new GetItemHandler($processor);
        $getItem = new GetItem([0.01], 'SODA');
        $handler($getItem);
    }

    public function testInsufficientMoneyException(): void
    {
        $this->expectException(InsufficientMoneyException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $processor
            ->expects(self::once())
            ->method('findItem')
            ->with('SODA')
            ->willReturn(Item::create('SODA', 1, Money::create(0.05)));

        $processor
            ->expects(self::once())
            ->method('extractItem')
            ->willThrowException(new InsufficientMoneyException('', 0, 0));

        $handler = new GetItemHandler($processor);

        $getItem = new GetItem([], 'SODA');
        $handler($getItem);
    }
}
