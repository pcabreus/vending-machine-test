<?php

namespace App\Application\UpdateItem;

use App\Domain\Exceptions\NotFoundItemException;
use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateItemHandler implements MessageHandlerInterface
{
    private ProcessorInterface $processor;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function __invoke(UpdateItem $addItem)
    {
        $item = $this->processor->findItem($addItem->getSelector());
        if (null === $item) {
            throw new NotFoundItemException($addItem->getSelector());
        }
        $item->setCount($addItem->getCount());

        return $this->processor->setItem($item);
    }
}