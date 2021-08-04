<?php

namespace App\Domain\Service;

use App\Domain\Exceptions\InsufficientMoneyException;
use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;

class VendingMachineProcessor implements ProcessorInterface
{
    /** @var Item[] */
    private array $totalItems;
    private CoinList $totalCoins;

    public function __construct()
    {
        $this->totalItems = [];
        $this->totalCoins = CoinList::create();
    }

    public function extractItem(Item $item, CoinList $entryCoins): CoinList
    {
        if (0 === $item->getCount()) {
            throw new NotFoundItemException($item);
        }

        $rest = $entryCoins->diff($item->getPrice());
        if (0 > $rest->getValue()) {
            throw new InsufficientMoneyException(
                $item->getSelector(),
                $item->getPrice()->toFloat(),
                $entryCoins->getTotal()->toFloat()
            );
        }

        $this->totalCoins->addCoinList($entryCoins);

        $item->decrease();

        return $this->totalCoins->getChange($rest);
    }

    public function findItem(string $selector): ?Item
    {
        return $this->totalItems[$selector] ?? null;
    }

    public function getTotalItems(): array
    {
        return $this->totalItems;
    }

    public function setTotalItems(array $totalItems): self
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    public function getTotalCoins(): CoinList
    {
        return $this->totalCoins;
    }

    public function setTotalCoins(CoinList $totalCoins): self
    {
        $this->totalCoins = $totalCoins;

        return $this;
    }
}