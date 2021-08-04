<?php

namespace App\Domain\Model;

use App\Domain\Exceptions\InvalidCoinException;

class Coin
{
    // As array of constant by simplicity
    public const ACCEPTED_AMOUNT = [0.05, 0.10, 0.25, 1.00];
    public const MAP = [
        5 => 0.05,
        10 => 0.10,
        25 => 0.25,
        100 => 1.00,
    ];
    private Money $amount;

    private function __construct(Money $value)
    {
        $this->amount = $value;
    }

    public static function create(float $floatAmount): self
    {
        if (!in_array($floatAmount, self::ACCEPTED_AMOUNT, true)) {
            throw new InvalidCoinException($floatAmount);
        }

        return new self(Money::create($floatAmount));
    }

    public function getAmount(): int
    {
        return $this->amount->getValue();
    }
}