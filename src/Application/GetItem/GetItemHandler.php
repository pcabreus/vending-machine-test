<?php

namespace App\Application\GetItem;

use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Model\CoinList;
use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetItemHandler implements MessageHandlerInterface
{
    private ProcessorInterface $processor;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function __invoke(GetItem $getItem): CoinList
    {
        if (null === $selectedItem = $this->processor->findItem($getItem->getSelector())) {
            throw new NotFoundItemException(sprintf('Invalid item name: `%s`', $getItem->getSelector()));
        }

        $coins = CoinList::create($getItem->getCoins());

        return $this->processor->extractItem($selectedItem, $coins);
    }

}