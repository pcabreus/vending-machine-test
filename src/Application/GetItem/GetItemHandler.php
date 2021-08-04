<?php

namespace App\Application\GetItem;

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
        return $this->processor->getItem($getItem->getItem(), $getItem->getCoins());
    }

}