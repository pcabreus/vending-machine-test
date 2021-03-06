<?php

namespace App\Domain\Service;

use App\Domain\Model\CoinList;
use App\Domain\Model\Item;

interface ProcessorInterface
{
    public function extractItem(Item $item, CoinList $entryCoins): CoinList;

    public function findItem(string $selector): ?Item;

    public function setItem(Item $item);

    public function getTotalItems(): array;

    public function setTotalItems(array $totalItems): self;

    public function getTotalCoins(): CoinList;

    public function setTotalCoins(CoinList $totalCoins): self;
}