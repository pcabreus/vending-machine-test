<?php

namespace App\Tests\Domain\Model;

use App\Domain\Exceptions\InvalidCoinException;
use App\Domain\Model\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{

    public function testToFloat()
    {
        $money = Money::create(0.05);
        self::assertEquals(5, $money->getValue());
        self::assertEquals(0.05, $money->toFloat());
    }

    public function testDiff()
    {
        $money = Money::create(0.05);
        $money->diff(1);
        self::assertEquals(4, $money->getValue());
        self::assertEquals(0.04, $money->toFloat());
    }

    public function testSum()
    {
        $money = Money::create(0.05);
        $money->sum(1);
        self::assertEquals(6, $money->getValue());
        self::assertEquals(0.06, $money->toFloat());
    }
}
