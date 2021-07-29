<?php

namespace App\Tests\Domain\Model;

use App\Domain\Exceptions\InvalidMoneyException;
use App\Domain\Model\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreateByFloat($floatAmount, $intAmount, $stringAmount)
    {
        $money = Money::createByFloat($floatAmount);
        self::assertEquals($intAmount, $money->getAmount());
        self::assertEquals($stringAmount, $money);
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

    public function testToFloat()
    {
        $money = Money::createByFloat(0.05);
        self::assertEquals(0.05, $money->toFloat());
    }

    /**
     * @dataProvider providerInvalidMoneyException
     */
    public function testInvalidMoneyException($floatAmount)
    {
        $this->expectException(InvalidMoneyException::class);
        Money::createByFloat($floatAmount);
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
