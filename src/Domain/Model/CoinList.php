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
        $this->addCoinAmount($coin->getAmount(), 1);

        return $this;
    }

    public function addCoins(Coin $coin, int $amount): self
    {
        $this->addCoinAmount($coin->getAmount(), $amount);

        return $this;
    }


    private function addCoinAmount(int $coinValue, int $amount): void
    {
        if (!isset($this->coins[$coinValue])) {
            $this->coins[$coinValue] = 0;
        }

        $this->coins[$coinValue] += $amount;
        $this->total = $this->total->sum($coinValue * $amount);
    }


    public function addCoinList(CoinList $coinList): self
    {
        foreach ($coinList->getCoins() as $value => $amount) {
            $this->addCoinAmount($value, $amount);
        }

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