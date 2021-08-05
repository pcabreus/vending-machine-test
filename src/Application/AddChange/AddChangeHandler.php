<?php

namespace App\Application\AddChange;

use App\Domain\Model\CoinList;
use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddChangeHandler implements MessageHandlerInterface
{
    private ProcessorInterface $processor;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function __invoke(AddChange $addChange)
    {
        $coinList = $this->processor->getTotalCoins();

        $newChange = CoinList::create($addChange->getCoins());
        $coinList->addCoinList($newChange);

        $this->processor->setTotalCoins($coinList);
    }

}