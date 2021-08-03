<?php

namespace App\Domain\Model;

class CoinList
{
    private Money $total;
    private array $coins;

    private function __construct()
    {
        $this->total = Money::create(0);
        $this->coins = [];
    }

    public static function create(): self
    {
        return new static();
    }

    public function getCoins(): array
    {
        return $this->coins;
    }

    public function getCount(Coin $coin)
    {
        return $this->coins[$coin->getAmount()];
    }

    public function addCoin(Coin $coin): self
    {
        if (!isset($this->coins[$coin->getAmount()])) {
            $this->coins[$coin->getAmount()] = 0;
        }
        ++$this->coins[$coin->getAmount()];
        $this->total = $this->total->sum($coin->getAmount());

        return $this;
    }

    public function addCoins(Coin $coin, int $amount): self
    {
        $this->coins[$coin->getAmount()] = $amount;

        $this->total = $this->total->sum($coin->getAmount() * $amount);

        return $this;
    }

    public function diff(Money $money): Money
    {
        return $this->total->diff($money->getValue());
    }

    public function getTotal(): Money
    {
        return $this->total;
    }
}