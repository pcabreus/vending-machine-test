<?php

namespace App\Domain\Model;

use App\Domain\Exceptions\NoChangeException;

class CoinList
{
    private Money $total;
    private array $coins;

    private function __construct()
    {
        $this->total = Money::create(0);
        $this->coins = [];
    }

    public static function create(array $csvCoins = []): self
    {
        $coinList = new static();

        foreach ($csvCoins as $floatCoins) {
            $coinList->addCoin(Coin::create($floatCoins));
        }

        return $coinList;
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

    // TODO Add test
    public function removeCoins(Coin $coin, int $amount): self
    {
        $coinValue = $coin->getAmount();
        if (!isset($this->coins[$coinValue])) {
            $this->coins[$coinValue] = 0;
        }

        $this->coins[$coinValue] -= $amount;
        $this->total = $this->total->diff($coinValue * $amount);

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

    public function getChange(Money $money): self
    {
        $change = self::create();

        if (0 === $money->getValue()) {
            return $change;
        }
        $coins = Coin::ACCEPTED_AMOUNT;
        while (count($coins) > 0) {
            $coin = Coin::create(array_pop($coins));
            $max = $this->calculateMaxAmount($money, $coin, $this->coins[$coin->getAmount()]);
            if (0 >= $max) {
                continue;
            }

            $money = $money->diff($coin->getAmount() * $max);
            $change->addCoins($coin, $max);
            $this->removeCoins($coin, $max);

            if (0 === $money->getValue()) {
                return $change;
            }
        }

        throw new NoChangeException($money->getValue());
    }

    private function calculateMaxAmount(Money $money, Coin $value, int $total): int
    {
        $result = $money->getValue() / $value->getAmount();
        if ($total > $result) {
            return $result;
        }

        return $total;
    }

    public function getTotal(): Money
    {
        return $this->total;
    }
}