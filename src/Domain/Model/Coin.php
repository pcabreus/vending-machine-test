<?php

namespace App\Domain\Model;

use App\Domain\Exceptions\InvalidMoneyException;

class Coin
{
    private const ACCEPTED_AMOUNT = [0.05, 0.10, 0.25, 1.00];
    private Money $amount;

    private function __construct(Money $value)
    {
        $this->amount = $value;
    }

    public static function create(float $floatAmount): self
    {
        if (!in_array($floatAmount, self::ACCEPTED_AMOUNT, true)) {
            throw new InvalidMoneyException($floatAmount);
        }

        return new self(Money::create($floatAmount));
    }

    public function getAmount(): int
    {
        return $this->amount->getValue();
    }
}