<?php

namespace App\IO;

use App\Application\GetItem\GetItem;
use App\Application\GetItem\GetItemHandler;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Service\ProcessorInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Reader
{
    private ProcessorInterface $vendingMachine;
    private MessageBusInterface $bus;

    public function __construct(ProcessorInterface $vendingMachine, MessageBusInterface $bus)
    {
        $this->vendingMachine = $vendingMachine;
        $this->bus = $bus;
    }

    public function read(string $input)
    {
        $parts = explode(',', $input);
        $action = trim(array_pop($parts));
        $list = CoinList::create();
        foreach ($parts as $part) {
            $list->addCoin(Coin::create($part));
        }

        switch ($action) {
            case 'GET-SODA':
            {
                return $this->bus->dispatch(new GetItem($list, Item::SELECTOR_SODA));
            }
            case 'GET-WATER':
                return new GetItem();
            case 'GET-JUICE':
                return new GetItem();
            case 'RETURN-COIN':
                return new GetItem();
            default:
                throw new InvalidInputException("The input action is not valid");
        }

    }
}