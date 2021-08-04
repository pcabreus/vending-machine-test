<?php

namespace App\Application\AddChange;

use App\Domain\Model\Coin;
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
        foreach ($addChange->getCoins() as $coin) {
            $coinList->addCoin(Coin::create($coin));
        }

        $this->processor->setTotalCoins($coinList);
    }

}