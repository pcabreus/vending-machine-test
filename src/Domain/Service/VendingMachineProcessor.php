<?php

namespace App\Domain\Service;

use App\Domain\Exceptions\InsufficientMoneyException;
use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Model\Money;

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

    public function on()
    {
        $this->totalItems = [
            Item::SELECTOR_SODA => Item::createSoda(5, Money::create(1.50)),
            Item::SELECTOR_JUICE => Item::createJuice(10, Money::create(1.00)),
            Item::SELECTOR_WATER => Item::createWater(50, Money::create(0.65)),
        ];

        $this->totalCoins = CoinList::create();
        foreach (['0.05' => 100, '0.10' => 50, '0.25' => 25, '1.00' => 10] as $coin => $count) {
            $this->totalCoins->addCoins(Coin::create($coin), $count);
        }
    }

    public function getItem(string $itemSelector, CoinList $entryCoins): CoinList
    {
        $selectedItem = $this->findItem($itemSelector);

        if (0 === $selectedItem->getCount()) {
            throw new NotFoundItemException($selectedItem);
        }

        $rest = $entryCoins->diff($selectedItem->getPrice());
        if (0 > $rest->getValue()) {
            throw new InsufficientMoneyException(
                $selectedItem->getSelector(),
                $selectedItem->getPrice()->toFloat(),
                $entryCoins->getTotal()->toFloat()
            );
        }

        $this->totalCoins->addCoinList($entryCoins);

        $selectedItem->decrease();

        return $this->totalCoins->getChange($rest);
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


    private function findItem(string $selector): Item
    {
        return $this->totalItems[$selector];
    }
}