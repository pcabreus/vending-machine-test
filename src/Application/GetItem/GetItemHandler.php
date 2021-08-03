<?php

namespace App\Application\GetItem;

use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetItemHandler implements MessageHandlerInterface
{
    private ProcessorInterface $processor;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function __invoke(GetItem $getItem)
    {
        $this->processor->getItem($getItem->getItem(), $getItem->getCoins());
    }

}