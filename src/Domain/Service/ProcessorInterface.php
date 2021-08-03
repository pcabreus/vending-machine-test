<?php

namespace App\Domain\Service;

use App\Domain\Model\CoinList;

interface ProcessorInterface
{
    public function on();

    public function getItem(string $itemSelector, CoinList $amount);

    public function getTotalItems(): array;

    public function setTotalItems(array $totalItems): self;

    public function getTotalCoins(): CoinList;

    public function setTotalCoins(CoinList $totalCoins): self;
}