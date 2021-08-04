<?php

namespace App\IO;

use App\Application\UpdateItem\UpdateItem;
use App\Application\GetItem\GetItem;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Item;
use App\Domain\Model\Money;
use App\Domain\Service\ProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class VendingMachineCommand extends Command
{
    protected static $defaultName = 'app:run';
    private ProcessorInterface $processor;
    private Writer $writer;
    private MessageBusInterface $bus;

    public function __construct(
        ProcessorInterface $processor,
        MessageBusInterface $bus,
        Writer $writer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->processor = $processor;
        $this->writer = $writer;
        $this->bus = $bus;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Run the vending machine');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $writer = $this->writer;
        $writer->withOutput($output);

        // On the vending machine. It came with some items and some money

        $this->installVendingMachineWithSomeProducts();
        $writer->writePresentation();
        $writer->writeStatus();

        //Listening to operation
        $helper = $this->getHelper('question');
        $question = new Question(
            "<info>Place your order or type `help` to see all options:</info>\n"
        );

        $operatorQuestion = new Question(
            "<info>Hello Operator. Update item by selector and count (e.g SODA, 20):</info>\n"
        );

        while (true) {
            if (!$request = $helper->ask($input, $output, $question)) {
                continue;
            }

            if ('exit' === $request) {
                return Command::SUCCESS;
            }

            if ('help' === $request) {
                $writer->writeHelp();
                continue;
            }

            if ('STATUS' === $request) {
                $writer->writeStatus();
                continue;
            }

            [$action, $list] = $this->read($request);

            if ('RETURN-COIN' === $action) {
                $writer->writeResult($list);
                continue;
            }

            if ('SERVICE' === $action) {
                if (!$newItem = $helper->ask($input, $output, $operatorQuestion)) {
                    continue;
                }

                try {
                    [$selectorName, $count] = explode(',', $newItem);
                    $this->bus->dispatch(new UpdateItem(trim($selectorName), trim($count)));
                } catch (\Exception $exception) {
                    $writer->writeError(
                        new InvalidInputException(
                            sprintf('Invalid format to enter new items, given `%s`', $newItem)
                        )
                    );
                    continue;
                }

                $writer->writeStatus();
                continue;
            }

            if (0 === strpos($action, 'GET-')) {
                $selector = substr($action, 4);

                if (null === $selectedItem = $this->processor->findItem($selector)) {
                    $this->writer->writeError(new InvalidInputException(sprintf('Invalid item name: `%s`', $selector)));
                    continue;
                }

                try {
                    $envelope = $this->bus->dispatch(new GetItem($list, $selectedItem));
                    $handledStamp = $envelope->last(HandledStamp::class);
                    /** @var CoinList $change */
                    $change = $handledStamp->getResult();
                    $writer->writeResult($change, $selector);
                } catch (HandlerFailedException $e) {
                    foreach ($e->getNestedExceptions() as $nestedException) {
                        $writer->writeError($nestedException);
                    }
                } catch (\Exception $e) {
                    $writer->writeError($e);
                }
                continue;
            }

            $writer->writeError(new InvalidInputException(sprintf('Invalid input: `%s`', $request)));
        }
    }

    private function read(string $input): array
    {
        $parts = explode(',', $input);
        $action = trim(array_pop($parts));
        $list = CoinList::create();
        foreach ($parts as $part) {
            $list->addCoin(Coin::create($part));
        }

        return [$action, $list];
    }

    private function installVendingMachineWithSomeProducts(): void
    {
        // current product
        $soda = 'SODA';
        $water = 'WATER';
        $juice = 'JUICE';
        $totalItems = [
            $soda => Item::create($soda, 5, Money::create(1.50)),
            $water => Item::create($water, 10, Money::create(1.00)),
            $juice => Item::create($juice, 50, Money::create(0.65)),
        ];
        $this->processor->setTotalItems($totalItems);

        $totalCoins = CoinList::create();
        foreach (['0.05' => 100, '0.10' => 50, '0.25' => 25, '1.00' => 10] as $coin => $count) {
            $totalCoins->addCoins(Coin::create($coin), $count);
        }
        $this->processor->setTotalCoins($totalCoins);
    }

}