<?php

namespace App\IO;

use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Writer
{
    private OutputInterface $output;
    private ProcessorInterface $vendingMachine;

    public function __construct(ProcessorInterface $vendingMachine)
    {
        $this->output = new NullOutput();
        $this->vendingMachine = $vendingMachine;
    }

    public function withOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    public function writePresentation(): void
    {
        $this->output->writeln(
            [
                '<info>Vending Machine</info>',
                '<info>===============</info>',
            ]
        );
    }

    public function writeStatus(): void
    {
        $this->output->writeln('<info>#Items</info>');
        foreach ($this->vendingMachine->getTotalItems() as $totalItem) {
            $this->output->writeln(
                sprintf('%s: %d - $%s', $totalItem->getSelector(), $totalItem->getCount(), $totalItem->getPrice())
            );
        }
        $this->output->writeln(
            [
                '<info>#Change in coins</info>',
                sprintf('$0.05: %d', $this->vendingMachine->getTotalCoins()->getCount(Coin::create(0.05))),
                sprintf('$0.10: %d', $this->vendingMachine->getTotalCoins()->getCount(Coin::create(0.10))),
                sprintf('$0.25: %d', $this->vendingMachine->getTotalCoins()->getCount(Coin::create(0.25))),
                sprintf('$1.00: %d', $this->vendingMachine->getTotalCoins()->getCount(Coin::create(1.00))),
            ]
        );
    }

    public function writeHelp(): void
    {
        $this->output->writeln(
            [
                '<info>#Help</info>',
                '<info>Expected command: List of coins separated by comma, and finally an action</info>',
                '<info>Actions:</info>',
                '<info>STATUS: Get the current status of the vending machine</info>',
                '<info>GET-<Item name>: Get the item. Available items: SODA, JUICE, WATER</info>',
                '<info>RETURN-COIN: Get all the coins entered</info>',
                '<info>Examples:</info>',
                'Example 1: Buy Soda with exact change
1, 0.25, 0.25, GET-SODA
-> SODA

Example 2: Start adding money, but user ask for return coin
0.10, 0.10, RETURN-COIN
-> 0.10, 0.10

Example 3: Buy Water without exact change
1, GET-WATER
-> WATER, 0.25, 0.10',
                '',
            ]
        );
    }

    public function writeError(\Throwable $exception): void
    {
        $this->output->writeln(
            [
                '<error>There is an error in your request</error>',
                sprintf('%s', $exception->getMessage()),
            ]
        );
    }

    public function writeResult(CoinList $change, string $item = null): void
    {
        $result = [];
        if ($item) {
            $result[] = $item;
        }

        if ($change->getTotal()->getValue() > 0) {
            $array = [];
            foreach ($change->getCoins() as $coin => $count) {
                $array[] = array_fill(0, $count, number_format(Coin::MAP[$coin], 2));
            }

            $result = array_merge($result, ...$array);
        }

        $this->output->writeln(
            [
                sprintf('-> %s', implode(', ', $result)),
            ]
        );
    }
}