<?php

namespace App\Tests\Domain\Model;

use App\Domain\Exceptions\InvalidCoinException;
use App\Domain\Model\Coin;
use PHPUnit\Framework\TestCase;

class CoinTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreateByFloat($floatAmount, $intAmount, $stringAmount)
    {
        $coin = Coin::create($floatAmount);
        self::assertEquals($intAmount, $coin->getAmount());
    }

    public function provider(): array
    {
        return [
            [0.05, 5, '0.05'],
            [0.10, 10, '0.10'],
            [0.25, 25, '0.25'],
            [1, 100, '1'],
        ];
    }

    /**
     * @dataProvider providerInvalidMoneyException
     */
    public function testInvalidMoneyException($floatAmount)
    {
        $this->expectException(InvalidCoinException::class);
        Coin::create($floatAmount);
    }

    public function providerInvalidMoneyException(): array
    {
        return [
            [0],
            [10],
            [0.55],
        ];
    }
}
