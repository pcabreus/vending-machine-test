<?php

namespace App\Tests\Application\AddChange;

use App\Application\AddChange\AddChange;
use App\Application\AddChange\AddChangeHandler;
use App\Domain\Exceptions\InvalidCoinException;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Service\ProcessorInterface;
use PHPUnit\Framework\TestCase;

class AddChangeHandlerTest extends TestCase
{

    public function testSuccess(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $totalCoins = CoinList::create();
        $processor
            ->expects(self::once())
            ->method('getTotalCoins')
            ->willReturn($totalCoins);

        $handler = new AddChangeHandler($processor);

        $addChange = new AddChange(['0.05', '1.00']);
        $expected = CoinList::create();
        $expected->addCoin(Coin::create(0.05));
        $expected->addCoin(Coin::create(1.00));
        $processor
            ->expects(self::once())
            ->method('setTotalCoins')
            ->with($expected);

        $handler($addChange);
    }

    public function testInvalidCoinException(): void
    {
        $this->expectException(InvalidCoinException::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $totalCoins = CoinList::create();
        $processor
            ->expects(self::once())
            ->method('getTotalCoins')
            ->willReturn($totalCoins);

        $handler = new AddChangeHandler($processor);

        $addChange = new AddChange(['0.01']);
        $handler($addChange);
    }
}
