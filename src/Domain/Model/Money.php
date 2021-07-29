<?php

namespace App\Domain\Model;

use App\Domain\Exceptions\InvalidMoneyException;

class Money
{
    private const ACCEPTED_AMOUNT = [0.05, 0.10, 0.25, 1.00];
    private int $amount;

    private function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public static function createByFloat(float $floatAmount): Money
    {
        if (!in_array($floatAmount, self::ACCEPTED_AMOUNT, true)) {
            throw new InvalidMoneyException($floatAmount);
        }
        $amount = (int)($floatAmount * 100);

        return new self($amount);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function toFloat(): float
    {
        return $this->amount / 100;
    }

    public function __toString(): string
    {
        return $this->toFloat();
    }

}