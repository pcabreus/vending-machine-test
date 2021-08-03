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
        $result = $money->diff(1);
        self::assertEquals(5, $money->getValue());
        self::assertEquals(4, $result->getValue());
        self::assertEquals(0.04, $result->toFloat());
    }

    public function testSum()
    {
        $money = Money::create(0.05);
        $result  = $money->sum(1);
        self::assertEquals(5, $money->getValue());
        self::assertEquals(6, $result->getValue());
        self::assertEquals(0.06, $result->toFloat());
    }
}
