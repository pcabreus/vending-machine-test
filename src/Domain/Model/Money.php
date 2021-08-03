<?php

namespace App\Domain\Model;

class Money
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function create(float $floatAmount): Money
    {
        $amount = (int)($floatAmount * 100);

        return new self($amount);
    }

    public function subtract(int $amount): Money
    {
        return new self($this->getValue() - $amount);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return $this->value / 100;
    }

    public function __toString(): string
    {
        return $this->toFloat();
    }

}